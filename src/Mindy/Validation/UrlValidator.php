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
 * @date 21/10/14.10.2014 14:18
 */

namespace Mindy\Validation;
use Mindy\Exception\InvalidConfigException;
use Mindy\Locale\Translate;

/**
 * UrlValidator validates that the attribute value is a valid http or https URL.
 *
 * Note that this validator only checks if the URL scheme and host part are correct.
 * It does not check the rest part of a URL.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UrlValidator extends Validator
{
    /**
     * @var string the regular expression used to validate the attribute value.
     * The pattern may contain a `{schemes}` token that will be replaced
     * by a regular expression which represents the [[validSchemes]].
     */
    public $pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
    /**
     * @var array list of URI schemes which should be considered valid. By default, http and https
     * are considered to be valid schemes.
     */
    public $validSchemes = ['http', 'https', 'ftp'];
    /**
     * @var boolean whether validation process should take into account IDN (internationalized
     * domain names). Defaults to false meaning that validation of URLs containing IDN will always
     * fail. Note that in order to use IDN validation you have to install and enable `intl` PHP
     * extension, otherwise an exception would be thrown.
     */
    public $enableIDN = false;

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        // make sure the length is limited to avoid DOS attacks
        if (is_string($value) && strlen($value) < 2000) {
            if (strpos($this->pattern, '{schemes}') !== false) {
                $pattern = str_replace('{schemes}', '(' . implode('|', $this->validSchemes) . ')', $this->pattern);
            } else {
                $pattern = $this->pattern;
            }

            if ($this->enableIDN) {
                if (!function_exists('idn_to_ascii')) {
                    throw new InvalidConfigException('In order to use IDN validation intl extension must be installed and enabled.');
                } else {
                    $value = preg_replace_callback('/:\/\/([^\/]+)/', function ($matches) {
                        return '://' . idn_to_ascii($matches[1]);
                    }, $value);
                }
            }

            if (!preg_match($pattern, $value)) {
                $this->addError(Translate::getInstance()->t('validation', '{attribute} is not a valid URL.', [
                    '{attribute}' => $this->name
                ]));
            }
        } else {
            $this->addError(Translate::getInstance()->t('validation', '{attribute} is not a valid URL.', [
                '{attribute}' => $this->name
            ]));
        }

        return $this->hasErrors() === false;
    }
}