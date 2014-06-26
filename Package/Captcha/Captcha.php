<?php
namespace Captcha;

use Captcha\Response;
use Captcha\Exception;

/**
 * Copyright (c) 2012, Aleksey Korzun <aleksey@webfoundation.net>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those
 * of the authors and should not be interpreted as representing official policies,
 * either expressed or implied, of the FreeBSD Project.
 *
 * Proper library for reCaptcha service
 *
 * @author Aleksey Korzun <aleksey@webfoundation.net>
 * @throws Exception
 * @package library
 */
class Captcha
{
    /**
     * reCaptcha's API server
     *
     * @var string
     */
    const SERVER = '//www.google.com/recaptcha/api';

    /**
     * reCaptcha's verify server
     *
     * @var string
     */
    const VERIFY_SERVER = 'www.google.com';

    /**
     * The Remote IP Address
     *
     * @var string
     */
    protected $remoteIp;

    /**
     * Private key
     *
     * @var string
     */
    protected $privateKey;

    /**
     * Public key
     *
     * @var string
     */
    protected $publicKey;

    /**
     * Custom error message to return
     *
     * @var string
     */
    protected $error;

    /**
     * The theme we use. The default theme is red, but you can change it using setTheme()
     *
     * @var string
     * @see https://developers.google.com/recaptcha/docs/customization
     */
    protected $theme = null;

    /**
     * An array of supported themes.
     *
     * @var string[]
     * @see https://developers.google.com/recaptcha/docs/customization
     */
    protected static $themes = array(
        'red',
        'white',
        'blackglass',
        'clean'
    );

    /**
     * Set public key
     *
     * @param string $key
     * @return reCaptcha
     */
    public function setPublicKey($key)
    {
        $this->publicKey = $key;
        return $this;
    }

    /**
     * Retrieve currently set public key
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set private key
     *
     * @param string $key
     * @return reCaptcha
     */
    public function setPrivateKey($key)
    {
        $this->privateKey = $key;
        return $this;
    }

    /**
     * Retrieve currently set private key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set remote IP
     *
     * @param string $ip
     * @return reCaptcha
     */
    public function setRemoteIp($ip)
    {
        $this->remoteIp = $ip;
        return $this;
    }

    /**
     * Get remote IP
     *
     * @return string
     */
    public function getRemoteIp()
    {
        if ($this->remoteIp) {
            return $this->remoteIp;
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Set error string
     *
     * @param string $error
     * @return reCaptcha
     */
    public function setError($error)
    {
        $this->error = (string) $error;
        return $this;
    }

    /**
     * Retrieve currently set error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Generates reCaptcha form to output to your end user
     *
     * @throws Exception
     * @return string
     */
    public function html()
    {
        if (!$this->getPublicKey()) {
            throw new Exception('You must set public key provided by reCaptcha');
        }

        $error = ($this->getError() ? '&amp;error=' . $this->getError() : null);

        $theme = null;

        // If user specified a reCaptcha theme, output it as one of the options
        if ($this->theme) {
            $theme = '<script> var RecaptchaOptions = {theme: "' . $this->theme . '"};</script>';
        }

        return $theme . '<script type="text/javascript" src="' . self::SERVER . '/challenge?k=' . $this->getPublicKey() . $error . '"></script>

        <noscript>
            <iframe src="' . self::SERVER . '/noscript?k=' . $this->getPublicKey() . $error . '" height="300" width="500" frameborder="0"></iframe><br/>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>';
    }

    /**
     * Checks and validates user's response
     *
     * @param bool|string $captcha_challenge Optional challenge string. If empty, value from $_POST will be used
     * @param bool|string $captcha_response Optional response string. If empty, value from $_POST will be used
     * @throws Exception
     * @return Response
     */
    public function check($captcha_challenge = false, $captcha_response = false)
    {
        if (!$this->getPrivateKey()) {
            throw new Exception('You must set private key provided by reCaptcha');
        }
        // Skip processing of empty data
        if (!$captcha_challenge && !$captcha_response) {
            if (isset($_POST['recaptcha_challenge_field']) && isset($_POST['recaptcha_response_field'])) {
                $captcha_challenge = $_POST['recaptcha_challenge_field'];
                $captcha_response = $_POST['recaptcha_response_field'];
            }
        }

        // Instance of response object
        $response = new Response();

        // Discard SPAM submissions
        if (strlen($captcha_challenge) == 0 || strlen($captcha_response) == 0) {
            $response->setValid(false);
            $response->setError('Incorrect-captcha-sol');
            return $response;
        }

        $process = $this->process(
            array(
                'privatekey' => $this->getPrivateKey(),
                'remoteip' => $this->getRemoteIp(),
                'challenge' => $captcha_challenge,
                'response' => $captcha_response
            )
        );

        $answers = explode("\n", $process [1]);

        if (trim($answers[0]) == 'true') {
            $response->setValid(true);
        } else {
            $response->setValid(false);
            $response->setError($answers[1]);
        }

        return $response;
    }

    /**
     * Make a signed validation request to reCaptcha's servers
     *
     * @throws Exception
     * @param array $parameters
     * @return string
     */
    protected function process($parameters)
    {
        // Properly encode parameters
        $parameters = $this->encode($parameters);

        $request  = "POST /recaptcha/api/verify HTTP/1.0\r\n";
        $request .= "Host: " . self::VERIFY_SERVER . "\r\n";
        $request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $request .= "Content-Length: " . strlen($parameters) . "\r\n";
        $request .= "User-Agent: reCAPTCHA/PHP5\r\n";
        $request .= "\r\n";
        $request .= $parameters;

        if (false == ($socket = @fsockopen(self::VERIFY_SERVER, 80))) {
            throw new Exception('Could not open socket to: ' . self::VERIFY_SERVER);
        }

        fwrite($socket, $request);

        $response = '';

        while (!feof($socket) ) {
            $response .= fgets($socket, 1160);
        }

        fclose($socket);

        return explode("\r\n\r\n", $response, 2);
    }

    /**
     * Construct encoded URI string from an array
     *
     * @param array $parameters
     * @return string
     */
    protected function encode(array $parameters)
    {
        $uri = '';

        if ($parameters) {
            foreach ($parameters as $parameter => $value) {
                $uri .= $parameter . '=' . urlencode(stripslashes($value)) . '&';
            }
        }

        $uri = substr($uri, 0, strlen($uri)-1);

        return $uri;
    }

    /**
     * Returns a boolean indicating if a theme name is valid
     *
     * @param string $theme
     * @return bool
     */
    protected static function isValidTheme($theme)
    {
        return (bool) in_array($theme, self::$themes);
    }

    /**
     * Set a reCaptcha theme
     *
     * @param string $theme
     * @throws Exception
     * @see https://developers.google.com/recaptcha/docs/customization
     */
    public function setTheme($theme)
    {
        if (!self::isValidTheme($theme)) {
            throw new Exception(
                'Theme ' . $theme . ' is not valid. Please use one of [' . join(', ', self::$themes) . ']'
            );
        }

        $this->theme = $theme;
    }
}

