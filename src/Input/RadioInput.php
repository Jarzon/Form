<?php
namespace Jarzon\Input;

use Jarzon\Bind;
use Jarzon\Input;
use Jarzon\ListBasedInput;
use Jarzon\Option;

class RadioInput extends ListBasedInput
{
    protected array $options = [];
    protected Bind $bind;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);

        $this->setAttribute('type', 'radio');

        $this->bind = new Bind();
    }

    public function generateHtml()
    {
        $html = [];

        $count = 0;
        foreach($this->options as $option) {
            $attr = $this->attributes;

            $attr['id'] = "{$this->name}_$count";

            $attr += $option->attr;

            if($this->selected === $attr['value']) {
                $attr['checked'] = null;
            }

            $html[] = ['label' => $option->text, 'id' => $attr['id'], 'html' => $this->generateTag($this->tag, $attr)];
            $count++;
        }

        foreach($this->bind->bindValues as $value) {
            $attr = $this->attributes;
            $attr['id'] = "{$this->name}_$count";

            foreach($this->bind->bindOptionAttributes as $name => $bind) {
                $attr[$name] = $value->$bind;
            }

            if($this->selected === $attr['value']) {
                $attr['checked'] = null;
            }

            $html[] = ['label' => $value->{$this->bind->bindOptionText}, 'id' => $attr['id'], 'html' => $this->generateTag($this->tag, $attr)];
            $count++;
        }

        $this->setHtml($html);
    }

    public function getRow()
    {
        $output = [];

        foreach ($this->getHtml() as $radio) {
            $output[] = $radio['html'] . $this->generateTag('label', ['for' => $radio['id']], $radio['label']);
        }

        return implode('', $output);
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        $bindValues = array_column($this->bind->bindValues, $this->bind->bindOptionAttributes['value']);
        $optionValues = array_column(array_column($this->options, 'attr'), 'value');

        if($value !== null) {
            $exist = false;

            if($key = array_search($value, $optionValues)) {
                $exist = true;
            }
            else if($key = array_search($value, $bindValues)) {
                $exist = true;
            }

            if(!$exist) {
                throw new \Error("$value doesn't exist");
            }
        }

        return true;
    }

    public function value($values = []): Input
    {
        $this->selected($values);

        return $this;
    }

    public function bindOptionText(string $name): Input
    {
        $this->bind->bindOptionText($name);

        return $this;
    }

    public function bindOptionValue(string $name): Input
    {
        $this->bind->bindOptionAttribute('value', $name);

        return $this;
    }

    public function bindOptionAttribute(string $attribute, string $name): Input
    {
        $this->bind->bindOptionAttributes[$attribute] = $name;

        return $this;
    }

    public function bindValues(array $values): Input
    {
        $this->bind->bindValues = $values;

        return $this;
    }

    public function addOption($text, $value, array $attr = [])
    {
        $attr += ['value' => $value];

        $this->options[] = new Option($text, $attr);
    }

    public function addOptions(array $values)
    {
        foreach ($values as $text => $value) {
            $this->addOption($text, $value);
        }
    }
}
