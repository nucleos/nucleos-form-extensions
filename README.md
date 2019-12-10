Form Extensions
===============
[![Latest Stable Version](https://poser.pugx.org/core23/form-extensions/v/stable)](https://packagist.org/packages/core23/form-extensions)
[![Latest Unstable Version](https://poser.pugx.org/core23/form-extensions/v/unstable)](https://packagist.org/packages/core23/form-extensions)
[![License](https://poser.pugx.org/core23/form-extensions/license)](LICENSE.md)

[![Total Downloads](https://poser.pugx.org/core23/form-extensions/downloads)](https://packagist.org/packages/core23/form-extensions)
[![Monthly Downloads](https://poser.pugx.org/core23/form-extensions/d/monthly)](https://packagist.org/packages/core23/form-extensions)
[![Daily Downloads](https://poser.pugx.org/core23/form-extensions/d/daily)](https://packagist.org/packages/core23/form-extensions)

[![Continuous Integration](https://github.com/core23/form-extensions/workflows/Continuous%20Integration/badge.svg)](https://github.com/core23/form-extensions/actions)
[![Code Coverage](https://codecov.io/gh/core23/form-extensions/branch/master/graph/badge.svg)](https://codecov.io/gh/core23/form-extensions)

This library adds some custom form elements and validation for symfony.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this library:

```
composer require core23/form-extensions
```

### Assets

It is recommended to use [webpack](https://webpack.js.org/) / [webpack-encore](https://github.com/symfony/webpack-encore)
to include the `Select2Autocomplete.js` file in your page. These file is located in the `assets` folder.

## Symfony usage

If you want to use this library inside symfony, you can use a bridge.

### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Core23\Form\Bridge\Symfony\Bundle\Core23FormBundle::class => ['all' => true],
];
```

## License

This library is under the [MIT license](LICENSE.md).
