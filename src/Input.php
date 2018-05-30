<?php
namespace Jarzon;

class Input
{
    protected $name = '';
    protected $value = '';
    public $label = null;
    public $html = '';
    protected $attributes = [];

    protected $min = null;
    protected $max = null;

    protected $pattern = null;

    public function __construct(string $name)
    {
        $this->setName($name);
        $this->setLabel($name);
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

    public function setValue(?string $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLabel(?string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setAttribute(string $name, $value = null)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    public function deleteAttribute(string $name)
    {
        if(!$this->hasAttribute($name)) {
            throw new \Exception("Trying to delete input attribute $name and it doesn't exist");
        }
        unset($this->attributes[$name]);
    }

    public function hasAttribute(string $name) : bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getHtml() : string
    {
        return $this->html;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function class(?string $classes = null)
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->setAttribute('class', $classes);
    }

    public function id(?string $id = null)
    {
        if($id === null) $id = $this->name;

        $this->setAttribute('id', $id);
    }

    public function label($label = null)
    {
        $this->setLabel($label);
    }

    public function value($value = '')
    {
        $this->setValue($value);

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

    public function generateTag(string $tag, array $attributes, $content = false) : string
    {
        $attr = '';

        foreach($attributes as $attribute => $value) {
            if($value === null) {
                $attr .= " $attribute";
            } else {
                $attr .= " $attribute=\"$value\"";
            }
        }

        $html = "<$tag$attr>";

        if($content !== false) {
            $html .= "$content</$tag>";
        }

        return $html;
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

    public function generateInput()
    {
        $this->setHtml($this->generateTag('input', $this->attributes));
    }

    public function __call($name, $arguments)
    {
        throw new \Exception("Illegal $name attribute on $this->name");
    }
}