<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class FloatInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 0.01);
    }

    public function validation()
    {
        return (float)parent::validation();
    }
}