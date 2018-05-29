<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class SelectInput extends ListBasedInput
{
    protected $values = [];

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function generateInput()
    {
        $content = '';

        foreach($this->values as $index => $attrValue) {
            $attr = ['value' => $attrValue];

            if($this->selected === $attrValue) {
                $attr['selected'] = null;
            }

            $content .= $this->generateTag('option', $attr, $index);
        }

        $this->setHtml($this->generateTag('select', $this->attributes, $content));
    }
}