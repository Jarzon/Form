<?php
namespace Jarzon;

class TextBasedInput extends Input
{
    public function min(int $min = 0)
    {
        $this->setAttribute('minlength', $min);

        $this->min = $min;
    }

    public function max(int $max = 0)
    {
        $this->setAttribute('maxlength', $max);

        $this->max = $max;
    }

    public function passValidation($value = null) : bool
    {
        $numberChars = mb_strlen($value);
        if(!empty($this->max) && $numberChars > $this->max) {
            throw new \Exception("{$this->name} is too long");
        }
        else if(!empty($this->min) && $numberChars < $this->min) {
            throw new \Exception("{$this->name} is too short");
        }

        return true;
    }
}