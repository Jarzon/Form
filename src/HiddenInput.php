<?php
namespace Jarzon;

class HiddenInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'hidden');
    }
}