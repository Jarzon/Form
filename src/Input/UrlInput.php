<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class UrlInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'url');
    }
}