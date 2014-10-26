<?php

/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 10/05/14.05.2014 15:50
 */

namespace Mindy\Validation;

use Mindy\Locale\Translate;

class UniqueValidator extends Validator
{
    public function validate($value)
    {
        $model = $this->getModel();
        $qs = $model::objects()->filter([$this->getName() => $value]);
        if (!$model->getIsNewRecord()) {
            $qs->exclude(['pk' => $model->pk]);
        }

        if ($qs->count() > 0) {
            $this->addError(Translate::getInstance()->t('validation', "{name} must be a unique", [
                '{name}' => $this->name
            ]));
        }

        return $this->hasErrors() === false;
    }
}