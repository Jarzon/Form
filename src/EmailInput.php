<?php
namespace Jarzon;

class EmailInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'email');
    }
}