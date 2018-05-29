<?php
namespace Jarzon;

class TextInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'text');
    }
}