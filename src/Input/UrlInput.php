<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class UrlInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'url');
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if(!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new ValidationException("$this->name is not a valid url");
        }

        return true;
    }
}