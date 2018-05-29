<?php
namespace Jarzon;

class Input
{
    protected $name = '';
    protected $value = '';
    public $label = '';
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

    public function setValue(string $value)
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

    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    public function deleteAttribute(string $name)
    {
        if(!isset($this->attributes[$name])) {
            throw new \Exception("Trying to delete input attribute $name and it doesn't exist");
        }
        unset($this->attributes[$name]);
    }

    public function getHtml() : string
    {
        return $this->html;
    }

    public function setHtml(string $html)
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

    public function pattern(?string $pattern = null)
    {
        $this->pattern = $pattern;

        if($pattern !== null) {
            $this->setAttribute('pattern', $pattern);
        } else {
            $this->deleteAttribute('pattern');
        }
    }

    public function required(bool $required = true)
    {
        if(!isset($this->attributes['required'])) {
            $this->setAttribute('required', null);
        }
        else {
            $this->deleteAttribute('required');
        }
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

    public function validation($value = null, $update = false)
    {
        if($value == '' && array_key_exists('required', $this->attributes)) {
            throw new \Exception("{$this->name} is required");
        }
        else if($value !== null) {
            $this->passValidation($value);
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

    public function generateInput()
    {
        $this->setHtml($this->generateTag('input', $this->attributes));
    }
}