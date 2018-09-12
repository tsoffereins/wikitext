# Wikitext Query
Wikitext Query is a PHP query object that helps you to crawl through wikitext markup language.

## Installation
Installation can be done using [Composer](https://getcomposer.org/) to download and install Wikitext Query as well as its dependencies.
```$xslt
composer require wikitext/query
```

## Usage
Before you can start to query anything you have to define the selectors that are available. You can create your own, although there is a set of matchers part of this package. All you need to do is add them:
```php
Wikitext\Query::addMatchers([
    new Wikitext\Matchers\HeaderMatcher('h1'), // h1, h2, ...
    new Wikitext\Matchers\UnorderedListMatcher(), // ul
    new Wikitext\Matchers\OrderedListMatcher(), // ol
    new Wikitext\Matchers\ListItemMatcher(), // li
    new Wikitext\Matchers\AnchorMatcher(), // a
    new Wikitext\Matchers\RowMatcher(), // hr
    new Wikitext\Matcher('selector', '/regex/')
]);
```
Creating a new Query object can be done by passing it the wikitext content.
```php
$query = new Wikitext\Query('wikitext');
```
The Query object will split the wikitext into single lines; alternatively you can also pass it an array with lines yourself:
```php
$query = new Wikitext\Query(['line 1', 'line 2', 'line 3']);
```

This initial Query (or main Query) serves as a something like the 'DOM'. Any action you perform on it, or any sub-query will always be related to this original object. This means that a sub-selection will still refer to the line numbers as present in the original wikitext.

The query object shows quite some similarity with jQuery when trying to query its content. Both the methods and the selectors are more or less the same. Each query action returns a new query in order to allow method chaining.
```php
$result = $query->find('h1')->next('hr');
```
*Please notice!* Query actions like `next` and `prev` always relate back to the initial wikitext.

## Documentation
### Crawl
#### Find
Returns a Query object with all lines from the initial wikitext that match the selector.
```php
Wikitext\Query find(string $selector)
```
When you use find on a Query object other than the main object it will filter from that.

#### Next
Returns a Query object with the line after the first item in the Query according to the initial wikitext. When the selector is specified it will return the line accordingly.
```php
Wikitext\Query next(string $selector = "*")
```
When none is found, an empty Query object is returned.

#### Prev
Returns a Query object with the line before the first item in the Query according to the initial wikitext. When the selector is specified it will return the line accordingly.
```php
Wikitext\Query prev(string $selector = "*")
```
When none is found, an empty Query object is returned.

#### Next all
Returns a Query object with the lines after the first item in the Query according to the initial wikitext. When the selector is specified it will return the lines accordingly.
```php
Wikitext\Query nextAll(string $selector = "*")
```
When none are found, an empty Query object is returned.

#### Prev all
Returns a Query object with the lines before the first item in the Query according to the initial wikitext. When the selector is specified it will return the lines accordingly.
```php
Wikitext\Query prevAll(string $selector = "*")
```
When none are found, an empty Query object is returned.

### Access
Items in the query object can be accessed in multiple ways.

#### Get text
Returns the lines of the Query object as a single string (imploded with "\n"").
```php
string getText()
```

#### Get items
Returns an array with all the lines in the Query object. The keys of the array will always correspond with their position in the initial wikitext.
```php
array getItems()
```

#### Is empty
Returns true if the Query has no lines.
```php
boolean isEmpty()
```

#### Count
Returns the number of lines in a Query.
```php
int count()
```

#### First
Returns a Query object with the first of line of the Query.
```php
Wikitext\Query first()
```

#### Last
Returns a Query object with the last of line of the Query.
```php
Wikitext\Query last()
```

#### Eq
Returns a Query object with the nth line of the Query.
```php
Wikitext\Query eq(int $index)
```

#### Match
Matches a regular expression against the wikitext and returns the defined index or the default value.
```php
mixed match(string $pattern, int $index = 0, $default = null)
```

### Countable, ArrayAccess & IteratorAggregate
A Query object can be treated as an array, but will always return its lines as Query objects.
```php
count($query); // x

var_dump($query[3]); // Wikitext\Query

foreach ($query as $line) {
    var_dump($line); // Wikitext\Query
}
```

## Support
Please file issues here at Github