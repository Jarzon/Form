<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class NumberInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 1);
    }

    public function validation()
    {
        return (int)parent::validation();
    }
}