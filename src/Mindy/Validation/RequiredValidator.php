<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 21/04/14.04.2014 18:29
 */

namespace Mindy\Validation;

use Mindy\Locale\Translate;

class RequiredValidator extends Validator
{
    public function validate($value)
    {
        if (empty($value)) {
            $this->addError(Translate::getInstance()->t('validation', "Value cannot be empty"));
        }

        return $this->hasErrors() === false;
    }
}

