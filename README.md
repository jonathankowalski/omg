# Open My Graph [![Build Status](https://travis-ci.org/jonathankowalski/omg.svg?branch=master)](https://travis-ci.org/jonathankowalski/omg)
A fast open graph parser

## Usage

```php
$parser = new JonathanKowalksi\Omg\Parser;

$tab = $parser->parse('http://www.enkulte.fr');

echo $tab['og:image']; //http://www.enkulte.fr/enkulte.jpg
```

## BTW

U can look for other meta tags if u want, just set it in constructor

```php

//twitter example
$parserTwitter = new JonathanKowalski\Omg\Parser('twitter');
$tab = $parserTwitter->parse('http://www.enkulte.fr');

echo $tab['twitter:site']; //@Enkulte

```

## Test with PhpUnit

```
curl -s https://getcomposer.org/installer | php
php composer.phar install --dev
./vendor/bin/phpunit
```