#!/bin/sh
set -e

ESLINT=$(which eslint || true)
if [ -z "$ESLINT" ]; then
    echo "Can't find command \"eslint\" in $PATH"
    exit 1
fi

echo Checking scripts with $ESLINT ...
find -name "*.js" -path '*js/*' -not -path '*js/node_modules*' \
    -not -path '*l10n/*' \
    -not -path '*js/vendor*' \
    -not -path '*js/tests*' \
    -not -path '*js/webpack*' \
    -not -path '*js/public*' \
    -not -path '*build/*' \
    -not -path '*tests/*' \
    -print0 | xargs -0 $ESLINT
