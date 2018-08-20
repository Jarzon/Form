<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class RadioInput extends ListBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->setAttribute('type', 'radio');
    }

    public function generateHtml()
    {
        $html = [];

        foreach($this->values as $index => $attrValue) {
            $attr = $this->attributes;

            $attr['value'] = $attrValue;

            if($this->selected === $attrValue) {
                $attr['checked'] = null;
            }

            $html[] = ['label' => $index, 'html' => $this->generateTag($this->tag, $attr)];
        }

        $this->setHtml($html);
    }

    public function getRow()
    {
        $output = [];

        foreach ($this->getHtml() as $radio) {
            $output[] = $this->generateTag('label', [], $radio['label'].$radio['html']);
        }

        return implode('', $output);
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if($value !== null) {
            $exist = false;

            if($key = array_search($value, $this->values)) {
                $value = $this->values[$key];
                $exist = true;
            }

            if(!$exist) {
                throw new \Error("$value doesn't exist");
            }
        }

        return true;
    }
}