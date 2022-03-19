<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Form;
use Jarzon\Input;
use Jarzon\ListBasedInput;

class CheckboxInput extends ListBasedInput
{
    public $value = true;
    public $negativeValue = false;

    public function __construct(string $name, Form $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'checkbox');
    }

    public function setNegativeValue($value): Input
    {
        $this->negativeValue = $value;

        return $this;
    }

    public function getRow(): string
    {
        return $this->getHtml().$this->getLabel();
    }

    public function value($value = ''): Input
    {
        $this->value = $value;

        $this->setAttribute('value', $value);

        return $this;
    }

    public function selected($selected = true) {
        if($selected) {
            $this->setAttribute('checked', null);
        } else if($selected === false && $this->hasAttribute('checked')) {
            $this->deleteAttribute('checked');
        }

        parent::selected($selected);

        return $this;
    }

    public function validation(): array|string|bool
    {
        $value = $this->getPostValue();

        if($this->form->repeat) {
            $values = [];
            // Iterate over the column for the current $input

            $numberOfLines = $this->form->getNumberOfRows();

            for($n = 0; $n <= $numberOfLines; $n++) {
                if(!isset($values[$n])) {
                    $values[] = [];
                }

                if(isset($value[$n])) {
                    $values[$n] = $this->value;
                } else {
                    $values[$n] = $this->negativeValue;
                }
            }

            return $values;
        }

        if($value !== null) {
            $value = $this->value;
        } else {
            $value = $this->negativeValue;
        }

        if(!$this->selected && $value !== $this->negativeValue) {
            $this->selected(true);
        }
        else if($this->selected && $value === $this->negativeValue) {
            $this->selected(false);
        }

        return $value;
    }
}
