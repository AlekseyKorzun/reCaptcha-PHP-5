<?php
/**
 * You must run `composer install` in order to generate autoloader for this example
 */
require __DIR__ . '/../vendor/autoload.php';

// New captcha instance
$captcha = new Captcha\Captcha();
$captcha->setPublicKey('publickey');
$captcha->setPrivateKey('privatekey');

// set a remote IP if the remote IP can not be found via $_SERVER['REMOTE_ADDR']
if (!isset($_SERVER['REMOTE_ADDR'])) {
    $captcha->setRemoteIp('192.168.1.1');
}


// Output captcha to end user
echo $captcha->html();

// Perform validation (put this inside if ($_POST) {} condition for example)
$response = $captcha->check();
if (!$response->isValid()) {
    echo $response->getError();
}

