<?php
namespace Jarzon;

class ColorInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'color');
    }
}