<?php
namespace Jarzon;

class TextInput extends Input
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'text');
    }

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

        if(!is_array($value)) {
            $this->setAttribute('value', $value);
        }
    }

    public function generateInput()
    {
        $this->setHtml($this->generateTag('input', $this->attributes));
    }

    public function validation($value = null, $update = false)
    {
        if($value == ''  && array_key_exists('required', $this->attributes)) {
            throw new \Exception("{$this->name} is required");
        }
        else if($value !== null) {

            $numberChars = mb_strlen($value);
            if(!empty($this->max) && $numberChars > $this->max) {
                throw new \Exception("{$this->name} is too long");
            }
            else if(!empty($this->min) && $numberChars < $this->min) {
                throw new \Exception("{$this->name} is too short");
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