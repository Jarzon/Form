<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class CurrencyInput extends DigitBasedInput
{
    public function __construct(string $name, string $inputType, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', $inputType);
        if($inputType === 'number') {
            $this->setAttribute('step', 0.01);
            $this->setAttribute('inputmode', 'decimal');
        }
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->value || $this->value !== null;
    }

    protected function convertValue($value): float
    {
        return (float)str_replace(' ', '', $value);
    }

    public function validation(): array|float|null
    {
        $value = parent::validation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = $this->convertValue($v);
            }

            return $value;
        }

        return $this->convertValue($value);
    }
}
