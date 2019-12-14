<?php
namespace Jarzon\Input;

use Jarzon\Bind;
use Jarzon\Input;
use Jarzon\ListBasedInput;

class SelectInput extends ListBasedInput
{
    protected $selected = null;
    protected array $groups = [];

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('select');
    }

    public function group(string $name)
    {
        $this->groups[$name] = [];
    }

    public function groupBind(string $name)
    {
        $this->groups[$name] = new Bind();
    }

    public function generateHtml()
    {
        $content = '';

        if(!empty($this->groups)) {
            foreach($this->groups as $groupName => $options) {
                $groupContent = '';

                if($options instanceof Bind) {
                    foreach($options->bindValues as $value) {
                        $groupContent .= $this->generateOption($value->{$options->bindOptionText}, $value->{$options->bindOptionAttributes['value']});
                    }
                } else {
                    foreach($options as $value) {
                        $groupContent .= $this->generateOption($value['text'], $value['value']);
                    }
                }

                if($groupName === 0) {
                    $content .= $groupContent;
                } else {
                    $content .= $this->generateTag('optgroup', ['label' => $groupName], $groupContent);
                }
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

    protected function &getLastOption()
    {
        return $this->groups[array_key_last($this->groups)];
    }

    public function bindOptionText(string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind(0);
        }

        $this->getLastOption()->bindOptionText($name);

        return $this;
    }

    public function bindOptionValue(string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind(0);
        }

        $this->getLastOption()->bindOptionAttribute('value', $name);

        return $this;
    }

    public function bindOptionAttribute(string $attribute, string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind(0);
        }

        $this->getLastOption()->bindOptionAttributes[$attribute] = $name;

        return $this;
    }

    public function bindValues(array $values): Input
    {
        if(empty($this->groups)) {
            $this->groupBind(0);
        }

        $this->getLastOption()->bindValues = $values;

        return $this;
    }

    public function addOption($text, $value)
    {
        if(empty($this->groups)) {
            $this->group(0);
        }

        $this->getLastOption()[] = ['text' => $text, 'value' => $value];
    }
}
