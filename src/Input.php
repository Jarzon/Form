<?php
namespace Jarzon;

class Input extends Tag
{
    public $name = '';
    protected $value = '';
    public $label = null;
    public $class = null;

    protected $min = null;
    protected $max = null;

    protected $pattern = null;

    public function __construct(string $name)
    {
        $this->setTag('input');
        $this->setName($name);
    }

    public function setName(string $name)
    {
        $this->setAttribute('name', $name);
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function class(?string $classes = null)
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->class = $classes;

        $this->setAttribute('class', $classes);
    }

    public function id(?string $id = null)
    {
        if($id === null) $id = $this->name;

        $this->setAttribute('id', $id);
    }

    public function label($label = null)
    {
        if($label !== null) {
            $label = $this->generateTag('label', ['for' => $this->name], $label);
            $this->id();
        }

        $this->label = $label;
    }

    public function value($value = '')
    {
        $this->value = $value;

        $this->setAttribute('value', $value);
    }

    public function placeholder(?string $placeholder = null)
    {
        $this->setAttribute('placeholder', $placeholder);
    }

    public function spellcheck(?bool $placeholder = null)
    {
        $this->setAttribute('spellcheck', ($placeholder) ? 'true': 'false');
    }

    public function autocomplete(?string $value = null)
    {
        $this->setAttribute('autocomplete', $value);
    }

    public function tabindex(?int $index = null)
    {
        $this->setAttribute('tabindex', $index);
    }

    public function pattern(?string $pattern = null)
    {
        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);
    }

    public function required(bool $required = true)
    {
        $this->setAttribute('required', null);
    }

    public function passValidation($value) : bool
    {
        return true;
    }

    public function isUpdated($value) : bool
    {
        $updated = false;

        if($value !== $this->value) {
            $updated = true;
        }

        return $updated;
    }

    public function validation($value = null, $update = false)
    {
        if($value == '' && array_key_exists('required', $this->attributes)) {
            throw new \Exception("{$this->name} is required");
        }
        else if($value !== null) {
            $this->passValidation($value);
        }

        $updated = $this->isUpdated($value);

        if($updated) {
            $this->value($value);
        }

        if($updated || (array_key_exists('required', $this->attributes) && !$update)) {
            return $value;
        }

        return null;
    }

    public function __call($name, $arguments)
    {
        throw new \Exception("Illegal $name attribute on $this->name");
    }
}