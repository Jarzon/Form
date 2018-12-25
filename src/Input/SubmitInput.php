<?php
namespace Jarzon\Input;

use Jarzon\Input;

class SubmitInput extends Input
{
    public function __construct(?string $name = null)
    {
        $this->setAttribute('type', 'submit');
        parent::__construct($name, $this);
    }

    public function validation()
    {
        return null;
    }
}