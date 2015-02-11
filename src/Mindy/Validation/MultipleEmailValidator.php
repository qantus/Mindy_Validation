<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 11/02/15 18:13
 */

namespace Mindy\Validation;

use Mindy\Locale\Translate;

class MultipleEmailValidator extends Validator
{
    /**
     * @param $value
     * @return mixed
     */
    public function validate($value)
    {
        $emails = explode(',', $value);
        $validator = new EmailValidator();
        foreach ($emails as $email) {
            if (!empty($email)) {
                if (!$validator->validate(trim($email))) {
                    $this->addError(Translate::getInstance()->t('validation', "{email} is not a valid email address", [
                        '{email}' => $email
                    ]));
                }
            }
        }
        return $this->hasErrors() === false;
    }
}
