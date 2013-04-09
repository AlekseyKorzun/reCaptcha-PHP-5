<?php
/**
 * You must run `composer install` in order to generate autoloader for this example
 */
require __DIR__ . '/../vendor/autoload.php';

// New captcha instance
$captcha = new Captcha\Captcha();
$captcha->setPublicKey('publickey');
$captcha->setPrivateKey('privatekey');

// Output captcha to end user
echo $captcha->html();

// Perform validation (put this inside if ($_POST) {} condition for example)
$response = $captcha->check();
if (!$response->isValid()) {
    echo $response->getError();
}

