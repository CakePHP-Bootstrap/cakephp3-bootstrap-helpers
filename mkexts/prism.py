from markdown import Extension
from markdown.extensions.fenced_code import FencedBlockPreprocessor


class PrismCodeExtension(Extension):
    """ Simple extension based on FencedBlockProcessor that will set correct
    language tag to use with prism.js. """

    def extendMarkdown(self, md, md_globals):
        """ Add PrismBlockPreprocessor to the Markdown instance. """
        md.registerExtension(self)
        md.preprocessors.add('fenced_code_block',
                             PrismBlockPreprocessor(md),
                             ">normalize_whitespace")


class PrismBlockPreprocessor(FencedBlockPreprocessor):
    """ Simple extension of FencedBlockPreprocessor that set correct language
    classes for prism.js. """

    def __init__(self, md):
        self.LANG_TAG = ' class="language-%s"'
        super(PrismBlockPreprocessor, self).__init__(md)
