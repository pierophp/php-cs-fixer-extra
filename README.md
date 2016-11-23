# PHP CS Extra Fixer

Some extra fixers for SensioLabs PHP CS Fixer. http://cs.sensiolabs.org/

## Instalation

```bash
$ composer require --dev pierophp/php-cs-fixer-extra
```

## Usage

Create a ".php_cs" in the project root with the example content:

```php
<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->files()
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('resources/views')
    ->exclude('storage')
    ->exclude('public')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return Symfony\CS\Config::create()
    ->addCustomFixer(new \PhpCsFixerExtra\Fixer\PhpdocFullNamespaceFixer())
    ->finder($finder)
    ->setUsingCache(true);
```

Run the command to fix:
```bash
$ ./vendor/bin/php-cs-fixer --config-file=./.php_cs fix my_file.php
```

## Configuring in GIT Pre-Commit

If you have GIT 2.9+ you can configure the PHP CS in the pre-commit.

Create a file with the path "hooks/pre-commit" in the project root with the following content:
```bash
#!/bin/bash

while read -r file;
do
  file=`echo ${file:1}`
  if [[ $file = *.php && -e $file && $file != *migrations* ]]; then
    ./vendor/bin/php-cs-fixer --config-file=./.php_cs fix $file
    git add $file
  fi
done < <(git diff --cached --name-status --diff-filter=ACM)	
```

After, add in git:
```bash
$ git config --add core.hooksPath hooks/
```