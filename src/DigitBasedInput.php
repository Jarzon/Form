<?php declare(strict_types=1);
namespace Jarzon;

class DigitBasedInput extends Input
{
    protected $value = 0;

    public function min(int $min = 0): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int $max = PHP_INT_MAX): static
    {
        $this->max = $max;

        return $this;
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        if($this->max !== null && $value > $this->max) {
            throw new ValidationException("{$this->name} is too high", 30);
        }
        else if($this->min !== null && $value < $this->min) {
            throw new ValidationException("{$this->name} is too low", 31);
        }

        return true;
    }

    public function isUpdated($value): bool
    {
        $value = (int)$value;

        return $value !== $this->value || ($this->value !== 0 && !$this->form->update);
    }
}
