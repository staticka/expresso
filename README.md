# Staticka Expresso

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

`Expresso` is a simple static blog platform based on [Staticka](https://github.com/staticka/staticka) which allows creating and building of pages through a web-based user interface.

## Installation

Install the `Expresso` package via [Composer](https://getcomposer.org/):

``` bash
$ composer require staticka/expresso
```

## Basic Usage

Initialize the `Express` class to create a new application:

``` php
// index.php

use Staticka\Expresso\Express;

// ...

$app = new Express;

// Set the URL of the Expresso app ------
$app->setAppUrl('http://localhost:3977');
// --------------------------------------

// Set the URL of the website ------
$app->setSiteUrl('https://roug.in');
// ---------------------------------

// Set the path to store the pages ----
$app->setPagesPath(__DIR__ . '/pages');
// ------------------------------------

$app->run();
```

To run the application, the [PHP's built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php) can be used:

``` bash
$ php -S localhost:3977 -t app/public
```

Once the application has been served, it is now possible to create pages using `Expresso`.

### Adding fields

Fields in `Expresso` are the additional data of a page (e.g., `name`, `link`, etc.). Use the `setFields` method to customize the specified fields:

``` php
// index.php

use Staticka\Expresso\Express;

// ...

$app = new Express;

$fields = array('name');
$fields[] = 'title';
$fields[] = 'link';
$fields[] = 'description';
$fields[] = 'plate';
$fields[] = 'category';
$fields[] = 'tags';

$app->setFields($fields);

// ...
```

## Building pages using `Console`

`Expresso` does not contain a functionality yet to build pages as they introduce performance issues when loading the application. However, an alternative way for building the requested pages is to install the [Console](https://github.com/staticka/console/) package then add the `Package` class by `Expresso`:

``` php
namespace Staticka\Expresso;

// ...

use Rougin\Slytherin\Integration\IntegrationInterface;

// ...

class Package implements IntegrationInterface
{
    // ...
}
```

To use the `Package` class of `Expresso` by `Console`, kindly create the required `staticka.yml` then include the specified class to `packages` property:

``` bash
$ composer require staticka/console
$ vendor/bin/staticka initialize
[PASS] "staticka.yml" added successfully!
```

``` yaml
# staticka.yml

root_path: %%CURRENT_DIRECTORY%%/app

# ...

packages:
  - Staticka\Expresso\Package
```

Once configured, select the `Build site` button from the application to compile all pages to HTML.

> [!NOTE]
> Kindly see the [Using `staticka.yml`](https://github.com/staticka/console/?tab=readme-ov-file#using-statickayml) section from `Console` for more information on how to configure the requested `staticka.yml` file.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

The [PHP's built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php) can be used for running the project:

``` bash
$ php -S localhost:3977 -t app/public
```

After running, open a web browser then proceed to http://localhost:3977.

> [!WARNING]
> This command should only be used for development purposes.

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/staticka/expresso/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/staticka/expresso?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/expresso.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/expresso.svg?style=flat-square

[link-build]: https://github.com/staticka/expresso/actions
[link-changelog]: https://github.com/staticka/expresso/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/staticka/expresso/contributors
[link-coverage]: https://app.codecov.io/gh/staticka/expresso
[link-downloads]: https://packagist.org/packages/staticka/expresso
[link-license]: https://github.com/staticka/expresso/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/expresso