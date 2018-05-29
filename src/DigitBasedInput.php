<?php
namespace Jarzon;

class DigitBasedInput extends Input
{
    public function min(int $min = 0)
    {
        $this->setAttribute('min', $min);

        $this->min = $min;
    }

    public function max(int $max = 0)
    {
        $this->setAttribute('max', $max);

        $this->max = $max;
    }

    public function class(?string $classes = null)
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->setAttribute('class', $classes);
    }

    public function label($label = false)
    {
        if($label) {
            $this->setLabel($label);
        } else {
            $this->setLabel(null);
        }
    }

    public function value($value = '')
    {
        $this->setValue($value);

        $this->setAttribute('value', $value);
    }

    public function generateInput()
    {
        $this->setHtml($this->generateTag('input', $this->attributes));
    }

    public function validation($value = null, $update = false)
    {
        if($value == '' && array_key_exists('required', $this->attributes)) {
            throw new \Exception("{$this->name} is required");
        }
        else if($value !== null) {
            if($this->max !== null && $value > $this->max) {
                throw new \Exception("{$this->name} is too high");
            }
            else if($this->min !== null && $value < $this->min) {
                throw new \Exception("{$this->name} is too low");
            }
        }

        $updated = false;

        if($value !== $this->value) {
            $updated = true;
        }

        if($updated) {
            $this->value($value);
        }

        if((array_key_exists('required', $this->attributes) && !$update) || $updated) {
            return $value;
        }

        return null;
    }
}