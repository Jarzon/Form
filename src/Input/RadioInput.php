<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\BindGroup;
use Jarzon\Input;
use Jarzon\ListBasedInput;
use Jarzon\Option;
use Jarzon\ValidationException;

class RadioInput extends ListBasedInput
{
    protected array $options = [];
    protected BindGroup $bind;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);

        $this->setAttribute('type', 'radio');

        $this->bind = new BindGroup();
    }

    public function generateHtml(): void
    {
        $html = [];

        $count = 0;
        foreach($this->options as $option) {
            $attr = $this->attributes;

            $attr['id'] = "{$this->name}_$count";

            $attr += $option->attr;

            if($this->selected !== null && $this->selected == $attr['value']) {
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

            if($this->selected !== null && $this->selected == $attr['value']) {
                $attr['checked'] = null;
            }

            $html[] = ['label' => $value->{$this->bind->bindOptionText}, 'id' => $attr['id'], 'html' => $this->generateTag($this->tag, $attr)];
            $count++;
        }

        $this->setHtml($html);
    }

    public function getRow(): string
    {
        $output = [];

        foreach ($this->getHtml() as $radio) {
            $output[] = $radio['html'] . $this->generateTag('label', ['for' => $radio['id']], $radio['label']);
        }

        return implode('', $output);
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        $bindValues = array_column($this->bind->bindValues, $this->bind->bindOptionAttributes['value']);
        $optionValues = array_column(array_column($this->options, 'attr'), 'value');

        if($value !== null) {
            $exist = false;

            if(in_array($value, $optionValues) || in_array($value, $bindValues)) {
                $exist = true;
            }

            if(!$exist) {
                throw new ValidationException("{$this->name} value isn't part of the list", 41);
            }
        }

        return true;
    }

    public function value($values = []): static
    {
        $this->selected($values);

        return $this;
    }

    public function bindOptionText(string $name): static
    {
        $this->bind->bindOptionText($name);

        return $this;
    }

    public function bindOptionValue(string $name): static
    {
        $this->bind->bindOptionAttribute('value', $name);

        return $this;
    }

    public function bindOptionAttribute(string $attribute, string $name): Input
    {
        $this->bind->bindOptionAttributes[$attribute] = $name;

        return $this;
    }

    public function bindValues(array $values): static
    {
        $this->bind->bindValues = $values;

        return $this;
    }

    public function addOption($text, $value, array $attr = [])
    {
        $attr += ['value' => $value];

        $this->options[] = new Option($text, $attr);
    }

    public function addOptions(array $values): static
    {
        foreach ($values as $text => $value) {
            $this->addOption($text, $value);
        }

        return $this;
    }
}
