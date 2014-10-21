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
 * @date 20/10/14.10.2014 20:03
 */

namespace Mindy\Validation\Interfaces;


interface IValidateObject
{
    /**
     * @return mixed the initialized fields for validation
     */
    public function getFieldsInit();

    /**
     * @param $attribute string
     * @return bool check the field isset
     */
    public function hasField($attribute);

    /**
     * @param $attribute string
     * @return object field instance
     */
    public function getField($attribute);
}
