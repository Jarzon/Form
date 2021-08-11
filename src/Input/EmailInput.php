<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\TextBasedInput;

class EmailInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'email');
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        $email = str_replace(' ', '', $value);

        $emails = explode(',', $email);

        foreach ($emails as $mail) {
            if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                throw new \Jarzon\ValidationException("$this->name is not a valid email");
            }
        }

        return true;
    }

    public function multiple(bool $multiple): Input
    {
        if($multiple) {
            $this->setAttribute('multiple');
        } else {
            $this->deleteAttribute('multiple');
        }

        return $this;
    }
}
