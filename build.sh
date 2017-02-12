#!/bin/bash

function abort_with_message() {
    echo $* > /dev/stderr
    exit 1
}

which mkdocs && mkdocs --version > /dev/null

if [ $? -ne 0 ]; then
    abort_with_message "Cannot find mkdocs, please install it using 'pip install mkdocs'."
fi

export PYTHONPATH=$PYTHONPATH:$(pwd)

mkdocs build

if [ $? -ne 0 ]; then
    abort_with_message "Fail to build site, aborting process."
fi

menu=$(grep -Pzo '(?s)(?<=<!-- helpers -->)(.*?)(?=</ul>)' site/index.html)
echo $menu | sed -E 's@</li>\s+@</li>\n@g' | sed -E 's@href="(.*?)"@href="/\1"@g' > build/templates/bootstrap/helpers-menu.latte

if [ $? -ne 0 ]; then
    abort_with_message "No menu found in site/index.html, aborting process."
fi

cd build

if [ ! -e repo ]; then
    git clone https://github.com/holt59/cakephp3-bootstrap-helpers.git repo
fi

cd repo && git fetch && git checkout v3.1.0 && cd ..

if [ ! -e apigen.phar ]; then
    echo "Fetching apigen.phar... "
    wget http://apigen.org/apigen.phar && chmod +x apigen.phar
    if [ $? -ne 0 ]; then
        abort_with_message "Unable to retrieve apigen.phar, aborting process."
    fi
fi

rm -rf api
php apigen.phar generate -s ./repo/src -d api  --debug

cd ..
mv build/api site/api


