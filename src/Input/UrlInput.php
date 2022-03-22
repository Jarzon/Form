<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class UrlInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'url');
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        if(!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new \Jarzon\ValidationException("$this->name is not a valid url", 27);
        }

        return true;
    }
}
