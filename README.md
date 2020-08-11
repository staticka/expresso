# Expresso

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Expresso is a simple flat-file and static blogging platform on top of [Staticka](https://roug.in/staticka/). It creates [Markdown](https://en.wikipedia.org/wiki/Markdown) files for the content and generates the static pages using [Parsedown](http://parsedown.org/) and [Twig](https://twig.symfony.com/) templating system.

## Installation

Install `Expresso` via [Composer](https://getcomposer.org/):

``` bash
$ composer require staticka/expresso
```

## Basic Usage

### Running the web application

Enter the following command in the terminal or command line to run its built-in web application:

``` bash
$ php -S 0.0.0.0:8006 -t vendor/staticka/expresso/app
```

After executing the command, kindly open http://localhost:8006 in the web browser.

**NOTE**: Change the port (e.g. `8006`) if it is being used by another application.

### Adding a new layout

A layout is needed first before creating a new post as it will be the basis for the base page. It can also be called as "theme" by others. To create a sample layout, create a file with `.twig` as its extension to be recognized by Expresso. For starters, a sample layout can be copied below for reference:

``` twig
<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ title }}</title>
</head>
<body>
  <div>
    {{ content | raw }}
  </div>
</body>
</html>
```

**NOTE**: Due to security reasons from [Twig](https://twig.symfony.com/), the `content` variable (which is responsible for displaying the whole output from a content) must be in [raw filter](https://twig.symfony.com/doc/2.x/filters/raw.html) as it will not be escaped in the output.

### Creating a first post

Click on the `New Page` link in the upper right of the screen to open the form for creating blog posts. The only required fields in the creating a blog post are `Name` and `Title`.

### Building static pages

Click on the `Build` link in the upper right of the screen to convert the Markdown content into static pages. The output of the recently generated static files are located in `build` directory.

## Adding additional data

It is possible to add additional data or configuration to Expresso. For convenience, add a new property in the `composer.json` named `expresso` and it will be used in the web application.

``` json
{
    "expresso":
    {
        "filters":
        [
            "Staticka\\Filter\\StyleMinifier",
            "Staticka\\Filter\\HtmlMinifier",
            "Staticka\\Filter\\ScriptMinifier"
        ],
        "variables":
        {
            "base_url": "https://staticka.github.io/",
            "github": "https://github.com/staticka",
            "website": "Staticka"
        },
        "website":
        {
            "name": "Staticka",
            "version": "v0.2.0"
        }
    },
    "require":
    {
        "staticka/expresso": "~0.1"
    }
}
```

### Filters

Filters are helpful utilities that can alter the output after being generated. Some notable examples are the `HtmlMinifier`, `StyleMinifier`, and `ScriptMinifier` which minifies specified elements in a static page.

### Variables

This section contains variables that can be passed for each blog post being generated. This might be useful when passing global variables such as the base URL, site name, or a text that must be available in all pages.

### Website

The variables on this section are only available for updating some data from the built-in web application. It is useful to check what site is currently being managed and its current version. As of now, the properties that are being used in the web application are `name` and `version` only.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-code-quality]: https://img.shields.io/scrutinizer/g/staticka/expresso.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/expresso.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/staticka/expresso.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/staticka/expresso/master.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/expresso.svg?style=flat-square

[link-changelog]: https://github.com/staticka/expresso/blob/master/CHANGELOG.md
[link-code-quality]: https://scrutinizer-ci.com/g/staticka/expresso
[link-contributors]: https://github.com/staticka/expresso/contributors
[link-downloads]: https://packagist.org/packages/staticka/expresso
[link-license]: https://github.com/staticka/expresso/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/expresso
[link-scrutinizer]: https://scrutinizer-ci.com/g/staticka/expresso/code-structure
[link-travis]: https://travis-ci.org/staticka/expresso