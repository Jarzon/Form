<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class TextareaInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setTag('textarea');
    }

    public function value($value = '')
    {
        $this->value = $value;
    }

    public function generateHtml()
    {
        $this->setHtml($this->generateTag($this->tag, $this->attributes, $this->value));
    }
}