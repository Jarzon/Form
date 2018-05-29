<?php
namespace Jarzon;

class NumberInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 1);
    }
}