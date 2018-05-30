<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class NumberInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 1);
    }

    public function validation($value = null, $update = false)
    {
        return (int)parent::validation($value, $update);
    }
}