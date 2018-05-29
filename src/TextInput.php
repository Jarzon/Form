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
}