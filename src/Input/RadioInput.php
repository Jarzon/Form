<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class RadioInput extends ListBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);

        $this->setAttribute('type', 'radio');
    }

    public function generateHtml()
    {
        $html = [];

        $count = 0;
        foreach($this->values as $index => $attrValue) {
            $attr = $this->attributes;

            $attr['id'] = "{$this->name}_$count";
            $attr['value'] = $attrValue;

            if($this->selected === $attrValue) {
                $attr['checked'] = null;
            }

            $html[] = ['label' => $index, 'html' => $this->generateTag($this->tag, $attr)];
            $count++;
        }

        $this->setHtml($html);
    }

    public function getRow()
    {
        $output = [];

        foreach ($this->getHtml() as $radio) {
            $output[] = $radio['html'] . $this->generateTag('label', [], $radio['label']);
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