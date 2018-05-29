<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class FloatInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 0.01);
    }


    public function validation($value = null, $update = false)
    {
        return (float)parent::validation($value, $update);
    }
}