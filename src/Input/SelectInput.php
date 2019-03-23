<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class SelectInput extends ListBasedInput
{
    protected $selected = '';

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('select');
    }

    public function __get($name)
    {
        $return = parent::__get($name);
        if($return) {
            return $return;
        }
        else if($name === 'values') {
            return $this->values;
        }
    }

    public function generateHtml()
    {
        $content = '';

        foreach($this->values as $index => $attrValue) {
            $attr = ['value' => $attrValue];

            $selectedValue = $this->getSelected();

            if(is_integer($attrValue)) {
                $selectedValue = (int)$selectedValue;
            } else if (is_float($attrValue)) {
                $selectedValue = (float)$selectedValue;
            }

            if($selectedValue === $attrValue) {
                $attr['selected'] = null;
            }

            $content .= $this->generateTag('option', $attr, $index);
        }

        $this->setHtml($this->generateTag($this->tag, $this->attributes, $content));
    }
}