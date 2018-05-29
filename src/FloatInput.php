<?php
namespace Jarzon;

class FloatInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 0.01);
    }
}