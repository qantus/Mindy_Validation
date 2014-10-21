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
 * @date 03/01/14.01.2014 21:59
 */

namespace Mindy\Validation;

use Mindy\Locale\Translate;

class MinLengthValidator extends Validator
{
    public $minLength;

    public function __construct($minLength)
    {
        $this->minLength = $minLength;
    }

    public function validate($value)
    {
        if(!is_string($value)) {
            $this->addError(Translate::getInstance()->t("{type} is not a string", ['{type}' => gettype($value)], 'validation'));
        }

        if (mb_strlen($value, 'UTF-8') < $this->minLength) {
            $this->addError(Translate::getInstance()->t("Minimal length is {length}", ['{length}' => $this->minLength], 'validation'));
        }

        return $this->hasErrors() === false;
    }
}
