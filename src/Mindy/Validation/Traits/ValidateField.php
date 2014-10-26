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
 * @date 20/10/14.10.2014 19:54
 */

namespace Mindy\Validation\Traits;

use Closure;
use Mindy\Validation\Validator;

trait ValidateField
{
    /**
     * @var \Mindy\Validation\Validator[]
     */
    public $validators = [];
    /**
     * @var array of errors
     */
    private $_errors = [];

    public function clearErrors()
    {
        $this->_errors = [];
    }

    public function isValid()
    {
        $this->clearErrors();

        foreach ($this->validators as $validator) {
            if ($validator instanceof Closure) {
                /* @var $validator Closure */
                /* @var $this \Mindy\Validation\Interfaces\IValidatorObject */
                $valid = $validator->__invoke($this->getValue());
                if ($valid !== true) {
                    if (!is_array($valid)) {
                        $valid = [$valid];
                    }

                    $this->addErrors($valid);
                }
            } else if ($validator instanceof Validator) {
                if($this->form instanceof \Mindy\Form\ModelForm) {
                    $validator->setModel($this->form->getInstance());
                }
                $validator->clearErrors();
                if ($validator->validate($this->getValue()) === false) {
                    $this->addErrors($validator->getErrors());
                }
            }
        }

        return $this->hasErrors() === false;
    }

    public function getErrors()
    {
        return array_unique($this->_errors);
    }

    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    public function addErrors($errors)
    {
        $this->_errors = array_merge($this->_errors, $errors);
    }

    public function addError($error)
    {
        $this->_errors[] = $error;
    }
}
