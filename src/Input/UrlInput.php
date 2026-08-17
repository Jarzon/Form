<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;
use Jarzon\ValidationException;

class UrlInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'url');
    }

    public function passValidation($value = null): ValidationException|bool
    {
        $err = parent::passValidation($value);
        if($err) return $err;

        if($value == '' && !$this->isRequired) return false;

        if(!filter_var($value, FILTER_VALIDATE_URL)) {
            return new ValidationException("$this->name is not a valid url", 27);
        }

        return false;
    }
}
