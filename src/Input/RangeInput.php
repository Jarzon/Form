<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class RangeInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'range');
    }
}