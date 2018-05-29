<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class SelectInput extends ListBasedInput
{
    protected $values = [];

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'select');
    }
}