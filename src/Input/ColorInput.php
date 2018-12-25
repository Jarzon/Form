<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class ColorInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'color');
    }
}