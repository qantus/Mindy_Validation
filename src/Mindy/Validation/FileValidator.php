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
 * @date 21/10/14.10.2014 15:13
 */

namespace Mindy\Validation;

use Mindy\Locale\Translate;

class FileValidator extends Validator
{
    public $allowedTypes = null;

    public function __construct($allowedTypes = null)
    {
        $this->allowedTypes = $allowedTypes;
    }

    public function validate($value)
    {
        $filename = '';
        if (is_array($value) && isset($value['name'])) {
            $filename = $value['name'];
        } else if (is_string($value)) {
            $filename = $value;
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if ($ext && is_array($this->allowedTypes) && !in_array($ext, $this->allowedTypes)) {
            $this->addError(Translate::getInstance()->t('validation', "Is not a valid file type {type}. Types allowed: {allowed}", [
                '{type}' => $ext,
                '{allowed}' => implode(', ', $this->allowedTypes)
            ]));
        }
        return $this->hasErrors() === false;
    }
}