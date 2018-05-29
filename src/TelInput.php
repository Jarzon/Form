<?php
namespace Jarzon;

class TelInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'tel');
    }
}