<?php declare(strict_types=1);
namespace Jarzon;

class TextBasedInput extends Input
{
    public function min(int $min = 0): static
    {
        $this->setAttribute('minlength', $min);

        $this->min = $min;

        return $this;
    }

    public function max(int $max = 0): static
    {
        $this->setAttribute('maxlength', $max);

        $this->max = $max;

        return $this;
    }

    public function passValidation($value = ''): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        $numberChars = mb_strlen((string)$value);
        if(!empty($this->max) && $numberChars > $this->max) {
            throw new ValidationException("{$this->name} is too long", 20);
        }
        else if(!empty($this->min) && $numberChars < $this->min) {
            throw new ValidationException("{$this->name} is too short", 21);
        }

        return true;
    }
}
