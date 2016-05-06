reCaptcha-PHP-5 (v2.0.0)
==========================

[![Latest Stable Version](https://poser.pugx.org/recaptcha/php5/v/stable)](https://packagist.org/packages/recaptcha/php5) [![Total Downloads](https://poser.pugx.org/recaptcha/php5/downloads)](https://packagist.org/packages/recaptcha/php5) [![License](https://poser.pugx.org/recaptcha/php5/license)](https://packagist.org/packages/recaptcha/php5)

**If you are using the reCaptcha field on any of your forms and are currently using global keys, you will need to set up a Site Key and a Private Key for each site where you use the reCaptcha field.**

A correctly coded PHP 5 reCaptcha class that will allow you to interact with Google's
reCaptcha API.

- 100% phpDocumentator 2 code coverage
- 100% PSR-2 code coverage
- Composer friendly package

Feel free to extend and modify it to fit your frameworks and applications needs.

Usage
-----

### Composer install
You can install via composer:
- visit http://getcomposer.org to install composer on your system;
- create a composer.json file in your project root:

```
{
  "require": {
      "recaptcha/php5": "v2.0.0"
  }
}
```
- download and install the package with `composer install`;
- add this line to your application's `index.php` file:

```php
<?php
require 'vendor/autoload.php';
```
### Manual install

If you have your autoloader, directly update namespaces and drop the files
into your frameworks library.

Please see Examples directory for a simple run down of functionality.

Notes
-----

- The functionality is based on $_POST requests by default; you can modify it (relatively easy) to take $_GET or whatever you want instead.
- It will always send a remote address from $_SERVER['REMOTE_ADDR'] variable. If you are behind Vagrant, etc. Please update it, or write a setter to set remote address on the fly.

About
-----

See: http://www.google.com/recaptcha/intro/index.html
