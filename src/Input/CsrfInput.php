<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class CsrfInput extends TextBasedInput
{
    public string $token;
    public string $pastToken;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'hidden');

        $this->token = bin2hex(random_bytes(10));

        $this->value($this->token);
        $this->required();

        if(isset($_SESSION['_formToken'])) {
            $this->pastToken = $_SESSION['_formToken'];
        }

        $_SESSION['_formToken'] = $this->token;
    }

    public function passValidation($value = null): void
    {
        parent::passValidation($value);

        if($value !== $this->pastToken) {
            throw new \Jarzon\ValidationException("the CSRF token doesn't match");
        }
    }
}
