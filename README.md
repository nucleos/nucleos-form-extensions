What is Core23FormExtensionsBundle?
===================================
[![Latest Stable Version](https://poser.pugx.org/core23/form-extensions-bundle/v/stable)](https://packagist.org/packages/core23/form-extensions-bundle)
[![Latest Unstable Version](https://poser.pugx.org/core23/form-extensions-bundle/v/unstable)](https://packagist.org/packages/core23/form-extensions-bundle)
[![License](https://poser.pugx.org/core23/form-extensions-bundle/license)](https://packagist.org/packages/core23/form-extensions-bundle)

[![Build Status](https://travis-ci.org/core23/FormExtensionsBundle.svg)](http://travis-ci.org/core23/FormExtensionsBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/core23/FormExtensionsBundle/badges/quality-score.png)](https://scrutinizer-ci.com/g/core23/FormExtensionsBundle/)
[![Code Climate](https://codeclimate.com/github/core23/FormExtensionsBundle/badges/gpa.svg)](https://codeclimate.com/github/core23/FormExtensionsBundle)
[![Coverage Status](https://coveralls.io/repos/core23/FormExtensionsBundle/badge.svg)](https://coveralls.io/r/core23/FormExtensionsBundle)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61081f5b-89e5-4594-93cb-281d4e1f536e/mini.png)](https://insight.sensiolabs.com/projects/51aa4b42-d229-4994-bb3a-156da22a1375)

[![Donate to this project using Flattr](https://img.shields.io/badge/flattr-donate-yellow.svg)](https://flattr.com/profile/core23)
[![Donate to this project using PayPal](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://paypal.me/gripp)

This bundle adds some custom form elements and validation to symfony.

### Installation

```
php composer.phar require core23/form-extensions-bundle
```

#### Enabling the bundle

```php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            
            new Core23\FormExtensionsBundle\Core23FormExtensionsBundle(),

            // ...
        );
    }
```

This lib / bundle is available under the [MIT license](LICENSE.md).
