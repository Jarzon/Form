<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;
use Jarzon\ValidationException;

class CsrfInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'hidden');

        $this->value = bin2hex(random_bytes(10));

        if(!isset($_SESSION['_formToken']) || !is_array($_SESSION['_formToken'])) {
            $_SESSION['_formToken'] = [];
        } else if (count($_SESSION['_formToken']) > 20) {
            $_SESSION['_formToken'] = array_slice($_SESSION['_formToken'], 1, 20);
        }

        $_SESSION['_formToken'][] = $this->value;

        $this->setAttribute('value', htmlspecialchars($this->value , ENT_QUOTES | ENT_SUBSTITUTE));

        $this->required();
    }

    public function value($value = ''): static
    {
        return $this;
    }

    public function passValidation($value = null): ValidationException|bool
    {
        $err = parent::passValidation($value);
        if($err) return $err;

        if(!$key = array_search($value, $_SESSION['_formToken'])) {
            return new ValidationException("the CSRF token doesn't match", 23);
        }

        unset($_SESSION['_formToken'][$key]);

        return false;
    }
}
