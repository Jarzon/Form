<?php
namespace Jarzon;

class RangeInput extends DigitBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'range');
    }
}