<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;
use Jarzon\ValidationException;

class CurrencyInput extends DigitBasedInput
{
    public int $decimals = 2;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'text');
        $this->setAttribute('inputmode', 'decimal');
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->value || $this->value !== null;
    }

    protected function convertValue($value): float
    {
        return (float)str_replace(' ', '', str_replace(',', '.', $value));
    }

    public function passValidation($value = null): bool
    {
        if((int)strpos(strrev($value), ".") > $this->decimals) {
            throw new ValidationException("{$this->name} have too many decimals", 30);
        }

        return parent::passValidation($value);
    }

    public function validation(): array|float|null
    {
        if($this->isDisabled) {
            return null;
        }
        $value = parent::validation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = $this->convertValue($v);
            }

            return $value;
        }

        return $this->convertValue($value);
    }

    public function generateHtml(): void
    {
        $this->setAttribute('oninput', "validator(this, {$this->min}, {$this->max}, {$this->decimals})");
        parent::generateHtml();
    }

    public function decimal(int $decimals = 2): void
    {
        $this->decimals = $decimals;
    }
}
