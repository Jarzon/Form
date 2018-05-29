<?php
namespace Jarzon;

class TextareaInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function value($value = '')
    {
        $this->setValue($value);
    }

    public function generateInput()
    {
        $this->setHtml($this->generateTag('textarea', $this->attributes, $this->value));
    }
}