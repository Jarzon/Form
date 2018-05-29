<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class SelectInput extends TextBasedInput
{
    protected $values = [];

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'text');
    }

    public function value($values = [])
    {
        $this->values = $values;
    }
}