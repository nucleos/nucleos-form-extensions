What is the Form Extensions PHP library?
========================================
[![Latest Stable Version](https://poser.pugx.org/core23/form-extensions/v/stable)](https://packagist.org/packages/core23/form-extensions)
[![Latest Unstable Version](https://poser.pugx.org/core23/form-extensions/v/unstable)](https://packagist.org/packages/core23/form-extensions)
[![License](https://poser.pugx.org/core23/form-extensions/license)](https://packagist.org/packages/core23/form-extensions)

[![Build Status](https://travis-ci.org/core23/FormExtensions.svg)](http://travis-ci.org/core23/FormExtensions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/core23/FormExtensions/badges/quality-score.png)](https://scrutinizer-ci.com/g/core23/FormExtensions/)
[![Coverage Status](https://coveralls.io/repos/core23/FormExtensions/badge.svg)](https://coveralls.io/r/core23/FormExtensions)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61081f5b-89e5-4594-93cb-281d4e1f536e/mini.png)](https://insight.sensiolabs.com/projects/51aa4b42-d229-4994-bb3a-156da22a1375)

[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle adds some custom form elements and validation to symfony.

### Installation

```
php composer.phar require core23/form-extensions
```

### Symfony usage

#### Enabling the bundle

```php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            
            new Core23\FormExtensions\Bridge\Symfony\Bundle\Core23FormExtensionsBundle(),

            // ...
        );
    }
```

This lib / bundle is available under the [MIT license](LICENSE.md).
