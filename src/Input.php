<?php
namespace Jarzon;

class Input
{
    protected $name = '';
    protected $value = '';
    public $label = '';
    public $html = '';
    protected $attributes = [];

    protected $min = 0;
    protected $max = 0;

    public function __construct(string $name)
    {
        $this->setName($name);
        $this->setLabel($name);
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

    public function class(?string $classes = null)
    {
        if($classes === null) {
            $classes = $this->lastRow['name'];
        }

        if($this->lastRow['type'] == 'radio') {
            $this->lastRow['class'] = $classes;
        } else {
            $this->lastRow['attributes']['class'] = $classes;
        }

        return $this;
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

    public function setHtml(string $html)
    {
        $this->html = $html;
    }

    public function getHtml() : string
    {
        return $this->html;
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

    public function validation()
    {

    }

    public function generateInput()
    {

    }
}