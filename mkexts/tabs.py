import re
import markdown

from collections import OrderedDict
from markdown.util import etree


class TabsExtension(markdown.Extension):
    """ Markdown extension to generate bootstrap tabs for documentation. """

    def __init__(self, *args, **kargs):
        super(TabsExtension, self).__init__(*args, **kargs)

    def extendMarkdown(self, md, md_globals):
        md.registerExtension(self)
        md.preprocessors.add('tabs_block', TabsBlockPreprocessor(md),
                             '>normalize_whitespace')
        md.treeprocessors.add('tabs_tree', TabsTreeProcessor(md), '_end')


class TabsBlockPreprocessor(markdown.preprocessors.Preprocessor):

    """ Block preprocessor that split the TAB instructions into different
    paragraph in order for the tree processor to work. """

    # Matches any instruction line of the tab extension
    TAG_RE = re.compile(r'(?P<tag>[ ]*--[ ]*TAB(S?)[ ]*:.*?\n)')

    def run(self, lines):
        """ This methods will add extra line around tab instruction so that
        the tab instruction are split into paragraph."""
        text = "\n".join(lines)
        text = re.sub(self.TAG_RE, "\n\g<tag>\n", text)
        return text.split("\n")


class TabsTreeProcessor(markdown.treeprocessors.Treeprocessor):

    SB_RE = re.compile(r'[ ]*--[ ]*TABS[ ]*:[ ]*(?P<name>.+)')
    EB_RE = re.compile(r'[ ]*--[ ]*TABS[ ]*')
    TA_RE = re.compile(r'[ ]*--[ ]*TAB[ ]*:[ ]*(?P<name>.+)')

    def run(self, root):
        in_tags = None
        to_remove = []
        cur_tabs, cur_tag = None, None
        for node in root.iter():
            if node.text:
                m = self.SB_RE.search(node.text)
                if m:
                    in_tags = OrderedDict()
                    cur_tabs = m.group('name')
                    to_remove.append(node)
                elif self.EB_RE.search(node.text):
                    node.tag = 'div'
                    node.clear()
                    self.createTabs(node, cur_tabs, in_tags)
                    in_tags = None
                elif in_tags is not None:
                    m = self.TA_RE.search(node.text)
                    if m:
                        cur_tag = m.group('name').strip()
                        in_tags[cur_tag] = []
                    else:
                        in_tags[cur_tag].append(node)
                    to_remove.append(node)
        for node in to_remove:
            root.remove(node)

    def makeId(self, name, tabName):
        """ Return a valid HTML id given the name of tab block and the name
        of the current tab."""
        return re.sub(r'[^a-z0-9_\-]+', '-',
                      '-'.join(['tab', name, tabName]).lower()) \
            .strip(' -')

    def createTabs(self, node, name, tabs):
        """ Create the DOM tree for the given tabs and append it to the
        given node.

        Parameters:
          - node The node to which the DOM tree will be append.
          - name The name of the tabulation block.
          - tabs An OrderedDict containing name of the tabs as key and
            a list of DOM elements as value.
        """
        activeId = self.makeId(name, list(tabs.keys())[0])
        ulElement = etree.Element('ul')
        ulElement.attrib['class'] = 'nav nav-tabs'
        ulElement.attrib['role'] = 'tablist'
        for tname in tabs:
            htmlId = self.makeId(name, tname)
            liElement = etree.Element('li')
            liElement.attrib['role'] = 'presentation'
            if htmlId == activeId:
                liElement.attrib['class'] = 'active'
            aElement = etree.Element('a')
            aElement.attrib['href'] = '#' + htmlId
            aElement.attrib['aria-controls'] = htmlId
            aElement.attrib['role'] = 'tab'
            aElement.attrib['data-toggle'] = 'tab'
            aElement.text = tname
            liElement.append(aElement)
            ulElement.append(liElement)
        node.append(ulElement)
        tabContent = etree.Element('div')
        tabContent.attrib['class'] = 'tab-content'
        for tname, elems in tabs.items():
            htmlId = self.makeId(name, tname)
            classes = ['tab-pane']
            if htmlId == activeId:
                classes.append('active')
            panel = etree.Element('div')
            panel.attrib['role'] = 'tabpanel'
            panel.attrib['class'] = ' '.join(classes)
            panel.attrib['id'] = htmlId
            for elem in elems:
                panel.append(elem)
            tabContent.append(panel)
        node.append(tabContent)


# http://pythonhosted.org/Markdown/extensions/api.html#makeextension
def makeExtension(*args, **kwargs):
    return TabsExtension(*args, **kwargs)
