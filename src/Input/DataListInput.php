<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\BindGroup;
use Jarzon\Input;
use Jarzon\ListBasedInput;
use Jarzon\Option;

class DataListInput extends Input
{
    protected array $groups = [];

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('input');
    }

    public function generateHtml(): void
    {
        $content = '';

        if(!empty($this->groups)) {
            foreach($this->groups as $groupName => $options) {
                $groupAttributes = ['label' => $groupName];
                $groupContent = '';

                if($options instanceof BindGroup) {
                    foreach($options->bindValues as $value) {
                        $attributes = [];

                        foreach($options->bindOptionAttributes as $name => $bind) {
                            $attributes[$name] = $value->$bind;
                        }

                        $groupContent .= $this->generateTag('option', $attributes);
                        $groupAttributes = $groupAttributes + $options->attributes;
                    }
                } else {
                    foreach($options as $option) {
                        $groupContent .= $this->generateTag('option', $option->attr);
                    }
                }

                if($groupName === 0) {
                    $content .= $groupContent;
                } else {
                    $content .= $groupContent;
                }
            }
        }

        $this->setHtml(
            $this->generateTag($this->tag, ['list' => "{$this->name}List"] + $this->attributes)
            . $this->generateTag('datalist', ['id' => "{$this->name}List"], $content)
        );
    }

    public function isUpdated($value): bool
    {
        return $value !== $this->value || $this->form->update;
    }

    public function group(string|int $name)
    {
        $this->groups[] = [];
    }

    public function groupBind(string|int $name, string $class = '')
    {
        $this->groups[$name] = new BindGroup($class);
    }

    public function setGroupAttribute($name, $value): DataListInput
    {
        $this->getLastOption()->setAttribute($name, $value);
        return $this;
    }

    protected function &getLastOption()
    {
        return $this->groups[array_key_last($this->groups)];
    }

    public function bindOptionText(string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind($name);
        }

        $this->getLastOption()->bindOptionText($name);

        return $this;
    }

    public function bindOptionValue(string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind($name);
        }

        $this->getLastOption()->bindOptionAttribute('value', $name);

        return $this;
    }

    public function bindOptionAttribute(string $attribute, string $name): Input
    {
        if(empty($this->groups)) {
            $this->groupBind($name);
        }

        $this->getLastOption()->bindOptionAttributes[$attribute] = $name;

        return $this;
    }

    public function bindValues(array $values): Input
    {
        if(empty($this->groups)) {
            $this->groupBind('default');
        }

        $this->getLastOption()->bindValues = $values;

        return $this;
    }

    public function addOption($text, $value, array $attr = [])
    {
        if(empty($this->groups)) {
            $this->group('default');
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
