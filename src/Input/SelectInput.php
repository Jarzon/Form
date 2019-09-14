<?php
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\ListBasedInput;

class SelectInput extends ListBasedInput
{
    protected $selected = null;
    protected $groups = [];

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('select');
    }

    public function group(string $name, array $options)
    {
        $this->groups[$name] = $options;
    }

    public function generateHtml()
    {
        $content = '';

        foreach($this->values as $name => $attrValue) {
            $content .= $this->generateOption($name, $attrValue);
        }

        if(!empty($this->groups)) {
            foreach($this->groups as $groupName => $options) {
                $groupContent = '';

                foreach($options as $name => $option) {
                    $groupContent .= $this->generateOption($name, $option);
                }

                $content .= $this->generateTag('optgroup', ['label' => $groupName], $groupContent);
            }
        }

        $this->setHtml($this->generateTag($this->tag, $this->attributes, $content));
    }

    public function generateOption($name, $value)
    {
        $attr = ['value' => $value];

        $selectedValue = $this->getSelected();

        if(is_integer($value)) {
            $selectedValue = (int)$selectedValue;
        } else if (is_float($value)) {
            $selectedValue = (float)$selectedValue;
        }

        if($selectedValue === $value) {
            $attr['selected'] = null;
        }

        return $this->generateTag('option', $attr, $name);
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->selected || ($this->value !== null && !$this->form->update);
    }

    public function value($values = []): Input
    {
        if(is_array($values)) {
            $this->values = $values;
        } else {
            $this->selected($values);
        }

        return $this;
    }
}