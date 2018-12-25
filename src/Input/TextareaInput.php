<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class TextareaInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('textarea');
    }

    public function value($value = '')
    {
        $this->value = $value;

        return $this;
    }

    public function generateHtml()
    {
        $this->setHtml($this->generateTag($this->tag, $this->attributes, $this->value));
    }
}