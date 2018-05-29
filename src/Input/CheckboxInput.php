<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class CheckboxInput extends ListBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'text');
    }
}