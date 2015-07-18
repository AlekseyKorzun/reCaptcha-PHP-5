reCaptcha-PHP-5 (v2.0.0)
==========================

A properly coded PHP 5 reCaptcha class that will allow you to interact with Google's
reCaptcha API.

- 100% phpDocumentator 2 code coverage
- 100% PSR-2 code coverage
- Composer friendly package

Feel free to extend and modify it to fit your frameworks / applications needs.

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

If you have your own autoloader, simply update namespaces and drop the files
into your frameworks library.

Please see Examples directory for a simple run down of functionality.

Notes
-----

- The functionality is based on $_POST requests by default, you can modify it (fairly easy) to take
$_GET or whatever you want instead.
- It will always send remote address from $_SERVER['REMOTE_ADDR'] variable if you are behind Vagrant/etc, please update it
(or write a setter to set remote address on the fly :))

About
-----

See: http://www.google.com/recaptcha/intro/index.html
