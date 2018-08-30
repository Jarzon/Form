<?php
namespace Jarzon;

class DigitBasedInput extends Input
{
    public function min(int $min = PHP_INT_MAX)
    {
        $this->setAttribute('min', $min);

        $this->min = $min;
    }

    public function max(int $max = 0)
    {
        $this->setAttribute('max', $max);

        $this->max = $max;
    }

    public function passValidation($value = null) : bool
    {
        if($this->max !== null && $value > $this->max) {
            throw new ValidationException("{$this->name} is too high");
        }
        else if($this->min !== null && $value < $this->min) {
            throw new ValidationException("{$this->name} is too low");
        }

        return true;
    }
}