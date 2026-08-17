<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;
use Jarzon\ValidationException;

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

    public function passValidation($value = null): ValidationException|bool
    {
        $err = parent::passValidation($value);
        if($err) return $err;

        if(!$this->isRequired && $value === '') return false;

        $emails = explode(',', str_replace(' ', '', $value));

        foreach ($emails as $email) {
            if(!preg_match("/$this->pattern/", $email)) {
                return new ValidationException("$this->name is not a valid email", 24);
            }
        }

        return false;
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
