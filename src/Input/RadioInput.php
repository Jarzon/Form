<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class RadioInput extends ListBasedInput
{
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