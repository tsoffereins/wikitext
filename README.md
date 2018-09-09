# Wikitext Query
Wikitext Query is a PHP query object that helps you to crawl through wikitext markup language.

## Installation
Installation can be done using [Composer](https://getcomposer.org/) to download and install Wikitext Query as well as its dependencies.
```$xslt
composer require wikitext/query
```

## Usage
Creating a new object can be done by passing it the wikitext content.
```php
$query = new Wikitext\Query('wikitext');
```

### Crawl
The query object shows quite some similarity with jQuery when trying to query its content. Both the methods and the selectors are more or less the same.
```php
$result = $query->find('h1');

$result = $result->next('hr');
```
Each query action returns a new query in order to allow method chaining.

### Selectors
You have to define your own selectors through matchers, although there is a set of matchers part of this package.
```php
Wikitext\Query::setMatchers([
    new Wikitext\Matchers\HeaderMatcher('h1'),
    new Wikitext\Matchers\HeaderMatcher('h3'),
    new Wikitext\Matchers\AnchorMAtcher(),
    new Wikitext\Matcher('foo', '/regex/')
]);
```

### Access
Items in the query object can be accessed in multiple ways.
```php
$items = $query->getItems();

$items = $query->first();

$items = $query->eq(4);
```
It also supports `ArrayAccess` and `Iteration`.

## Support
Please file issues here at Github