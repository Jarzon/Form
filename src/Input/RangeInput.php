<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class RangeInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'range');
    }
}