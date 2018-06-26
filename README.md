# Pagination

Easily use pagination for your repositories and in your templates.

## Requirements

This package requires PHP 5.6+.

## Installation

This package can be used in any PHP project or with any framework. The packages is tested in PHP 5.6 and PHP 7.0.

You can install the package via composer:

```
composer require vdhicts/pagination
```

## Usage

The pagination class is designed to be as fluent as possible. When you just want to use it as offset, limit holder or 
want to use it for providing feedback to the user about the pagination, this class tries to solve it all for you.

The most basic usage doesn't need any parameters. This isn't very useful though, because it doesn't known anything 
about the items. You should try to at least use a limit or page: 

```php
use Vdhicts\Dicms\Pagination\Pagination;

$limit = 25;
$page = 2;

$pagination = new Pagination($limit, $page);
```

This results in:

Method | Result
--- | ---
getLimit | 25
hasLimit | true
getOffset | 25
getFirstItemOnPage | 26
getLastItemOnPage | 50

When using it with the total items available, it will offer more information:

```php
$limit = 25;
$page = 2;
$totalItems = 54;

$pagination = new Pagination($limit, $page, $totalItems);
```

This results in:

Method | Result
--- | ---
getLimit | 25
hasLimit | true
getOffset | 25
getTotalItems | 54
getPage | 2
getTotalPages | 3
getFirstItemOnPage | 26
getLastItemOnPage | 50

### Interface/Typehinting

The pagination implements the `Paginator` interface. So when you want to typehint a pagination object it's advised to 
typehint the interface. This will provide exchangeability between implementations.

### Exceptions

Exceptions thrown by this package always extend the `PaginationException`, so when you want to catch any exceptions of 
this package you should catch the `PaginationException`. 

### Methods

The following methods can be accessed depending on the provided information:

Method | Description
--- | ---
getLimit | returns the limit
hasLimit | determines if any limit is used
getOffset | returns the offset
getTotalItems | returns the total amount of items (allows returns useful information when the total amount of items is provided)
getPage | returns the current page
getTotalPages | returns the total amount of pages (allows returns useful information when the total amount of items is provided)
getFirstItemOnPage | returns the first item number on the current page
getLastItemOnPage | returns the last item number on the current page
getParameters |  returns the extra parameters used in pagination links

### Templates

When using bootstrap and Twig you can use the following template for the pagination:

```twig
<ul class="pagination justify-content-center">
    <li class="page-item{{ pagination.getTotalPages == 1 or pagination.getPage == 1 ? ' disabled' : '' }}">
        <a class="page-link" href="?page={{ pagination.getPage - 1 }}&{{ pagination.getParameters|url_encode }}">
            Previous
        </a>
    </li>
    {% for page in range(1, pagination.getTotalPages) %}
    <li class="page-item{{ pagination.getPage == page ? ' active' : '' }}">
        <a class="page-link" href="?page={{ page }}&{{ pagination.getParameters|url_encode }}">
            {{ page }}
        </a>
    </li>
    {% endfor %}
    <li class="page-item{{ pagination.getTotalPages == 1 or pagination.getPage == pagination.getTotalPages ? ' disabled' : '' }}">
        <a class="page-link" href="?page={{ pagination.getPage + 1 }}&{{ pagination.getParameters|url_encode }}">
            Next
        </a>
    </li>
</ul>
```

When you want to display the first, last and total items, you could add the following template:

```twig
<p>
    Showing {{ pagination.getFirstItemOnPage }} - {{ pagination.getLastItemOnPage }} of {{ pagination.getTotalItems }}
</p>
```

## Tests

Full code coverage unit tests are available in the tests folder. Run via phpunit:

`vendor\bin\phpunit` 

By default a coverage report will be generated in the build/coverage folder.

## Contribution

Any contribution is welcome, but it should be fully tested, meet the PSR-2 standard and please create one pull request 
per feature. In exchange you will be credited as contributor on this page.

## Security

If you discover any security related issues in this or other packages of Vdhicts, please email info@vdhicts.nl instead
of using the issue tracker.

## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

## About vdhicts

[Van der Heiden ICT services](https://www.vdhicts.nl) is the name of my personal company for which I work as
freelancer. Van der Heiden ICT services develops and implements IT solutions for businesses and educational
institutions.
