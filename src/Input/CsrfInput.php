<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class CsrfInput extends TextBasedInput
{
    public string $token;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'hidden');

        $this->token = bin2hex(random_bytes(10));

        $this->value($this->token);
        $_SESSION['_formToken'] = $this->token;
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if($value !== $_SESSION['_formToken']) {
            throw new \Jarzon\ValidationException("the CSRF token doesn't match");
        }

        return true;
    }
}
