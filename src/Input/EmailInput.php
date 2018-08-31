<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class EmailInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'email');
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Jarzon\ValidationException("$this->name is not a valid email");
        }

        return true;
    }
}