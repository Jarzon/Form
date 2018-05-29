<?php
namespace Jarzon;

class Input
{
    protected $name = '';
    protected $value = '';
    protected $label = '';
    protected $attributes = [];

    public function __construct(string $name)
    {

    }

    public function setName(string $name)
    {
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

    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function validation()
    {

    }
}