<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class HiddenInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'hidden');
    }
}