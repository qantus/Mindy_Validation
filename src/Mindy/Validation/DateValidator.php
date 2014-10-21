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
 * @date 21/10/14.10.2014 14:31
 */

namespace Mindy\Validation;


use DateTime;
use Mindy\Locale\Translate;

class DateValidator extends Validator
{
    /**
     * @var string Y-m-d or Y-m-d H:i:s as example
     */
    public $format = 'Y-m-d';

    public function __construct($format = 'Y-m-d')
    {
        $this->format = $format;
    }

    public function validate($value)
    {
        if (is_object($value) && !$value instanceof DateTime) {
            $this->addError(Translate::getInstance()->t('validation', "{type} is not a string or DateTime object", ['{type}' => gettype($value)]));
        } else {
            $dateTime = DateTime::createFromFormat($this->format, $value);
            if ($dateTime === false || $dateTime->format($this->format) != $value) {
                $this->addError(Translate::getInstance()->t('validation', 'Incorrect date format'));
            }
        }

        return $this->hasErrors() === false;
    }
}
