<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class TextInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'text');
    }
}