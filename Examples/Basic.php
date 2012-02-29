<?php
namespace Application;
use Library\Captcha;

/**
 * No autoloader
 */ 
require '../Captcha.php';

// New captcha instance
$captcha = new Captcha();
$captcha->setPublicKey('publickey');
$captch->setPrivateKey('privatekey');

// Output captcha to end user
echo $captcha->html();

// Perform validation (put this inside if ($_POST) {} condition for example)
$response = $captcha->check();
if (!$response->isValid()) {
    echo $response->getError();
}

