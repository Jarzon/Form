<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class EmailInput extends TextBasedInput
{
    protected string|null $pattern = '^[^\s@]+@[^\s@]+\.[^\s@]+$';
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this
            ->setAttribute('type', 'email')
            ->setAttribute('pattern', $this->pattern);
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        $email = str_replace(' ', '', $value);

        $emails = explode(',', $email);

        foreach ($emails as $mail) {
            if(!preg_match("/$this->pattern/", $value)) {
                throw new \Jarzon\ValidationException("$this->name is not a valid email", 24);
            }
        }

        return true;
    }

    public function multiple(bool $multiple = true): static
    {
        if($multiple) {
            $this->setAttribute('multiple');
        } else {
            $this->deleteAttribute('multiple');
        }

        return $this;
    }
}
