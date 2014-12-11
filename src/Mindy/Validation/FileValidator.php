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
use Symfony\Component\Console\Helper\FormatterHelper;

class FileValidator extends Validator
{
    /**
     * @var null
     */
    public $allowedTypes = [];
    /**
     * @var null|int maximum file size or null for unlimited. Default value 2 mb.
     */
    public $maxSize;
    /**
     * @var bool
     */
    public $null;

    public function __construct($null, $allowedTypes = null, $maxSize = 2097152)
    {
        $this->allowedTypes = $allowedTypes;
        $this->null = $null;
        $this->maxSize = $maxSize;
    }

    protected function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;
            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;
    }

    public function validate($value)
    {
        $model = $this->getModel();
        if (is_array($value)) {
            $v = $model ? $model->{$this->name}->getValue() : null;
            if (
                empty($v) &&
                $value['error'] != UPLOAD_ERR_OK &&
                $this->null !== false &&
                $value['error'] != UPLOAD_ERR_NO_FILE
            ) {
                $this->addError(Translate::getInstance()->t('validation', $this->codeToMessage($value['error'])));
            }

            if ($this->maxSize !== null && $value['size'] > $this->maxSize) {
                $this->addError(Translate::getInstance()->t('validation', 'Maximum uploaded file size: {size}', [
                    '{size}' => FormatterHelper::formatMemory($this->maxSize)
                ]));
            }
        }

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
