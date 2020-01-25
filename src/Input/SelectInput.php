<?php
namespace Jarzon\Input;

use Jarzon\Bind;
use Jarzon\Input;
use Jarzon\ListBasedInput;
use Jarzon\Option;

class SelectInput extends ListBasedInput
{
    protected $selected = null;
    protected array $groups = [];

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('select');
    }

    public function generateHtml()
    {
        $content = '';

        if(!empty($this->groups)) {
            foreach($this->groups as $groupName => $options) {
                $groupContent = '';

                if($options instanceof Bind) {
                    foreach($options->bindValues as $value) {
                        $attributes = [];

                        foreach($options->bindOptionAttributes as $name => $bind) {
                            $attributes[$name] = $value->$bind;
                        }

                        $groupContent .= $this->generateOption($value->{$options->bindOptionText}, $attributes);
                    }
                } else {
                    foreach($options as $option) {
                        $groupContent .= $this->generateOption($option->text, $option->attr);
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

    public function generateOption($name, $attr = [])
    {
        $selectedValue = $this->getSelected();

        if(is_integer($attr['value'])) {
            $selectedValue = (int)$selectedValue;
        } else if (is_float($attr['value'])) {
            $selectedValue = (float)$selectedValue;
        }

        if($selectedValue === $attr['value']) {
            $attr['selected'] = null;
        }

        return $this->generateTag('option', $attr, $name);
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->selected || ($this->value !== null && !$this->form->update);
    }

    /** @param string|int $name */
    public function group($name)
    {
        $this->groups[$name] = [];
    }

    /** @param string|int $name */
    public function groupBind($name)
    {
        $this->groups[$name] = new Bind();
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

    public function addOption($text, $value, array $attr = [])
    {
        if(empty($this->groups)) {
            $this->group(0);
        }

        $attr += ['value' => $value];

        $this->getLastOption()[] = new Option($text, $attr);
    }

    public function addOptions(array $values)
    {
        foreach ($values as $text => $value) {
            $this->addOption($text, $value);
        }
    }
}
