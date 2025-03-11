<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class FloatInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 0.01);
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->value || $this->value !== null;
    }

    public function inputValidation(): array|float|null
    {
        $value = parent::inputValidation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = (float)$v;
            }

            return $value;
        }

        return (float)$value;
    }

    public function min(int $min = 0): static
    {
        parent::min($min);
        $this->setAttribute('min', $min);
        return $this;
    }

    public function max(int $max = PHP_INT_MAX): static
    {
        parent::max($max);
        $this->setAttribute('max', $max);
        return $this;
    }
}
