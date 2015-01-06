<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 17/12/14 14:57
 */

namespace Mindy\Validation;

use Mindy\Helper\Json;
use Mindy\Locale\Translate;

class RecaptchaValidator extends Validator
{
    /**
     * @var string
     */
    public $publicKey;
    /**
     * @var string
     */
    public $secretKey;
    /**
     * @var string
     */
    public $message = "Incorrect captcha. Please try again.";

    /**
     * @param $publicKey
     * @param $secretKey
     */
    public function __construct($publicKey, $secretKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $url = strtr('https://www.google.com/recaptcha/api/siteverify?secret={secret}&response={response}&remoteip={remoteip}', [
                '{secret}' => $this->secretKey,
                '{response}' => $_POST['g-recaptcha-response'],
                '{remoteip}' => $_SERVER['REMOTE_ADDR'],
            ]);
            $data = Json::decode(file_get_contents($url));
            if (!isset($data['success']) || $data['success'] === false) {
                $this->addError(Translate::getInstance()->t('validation', $this->message, [
                    '{name}' => $this->getName()
                ]));
            }
        } else {
            $this->addError(Translate::getInstance()->t('validation', $this->message, [
                '{name}' => $this->getName()
            ]));
        }

        return $this->hasErrors() === false;
    }
}
