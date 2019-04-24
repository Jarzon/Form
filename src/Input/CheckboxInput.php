<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class CheckboxInput extends ListBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'checkbox');
    }

    public function value($value = '')
    {
        $this->value = $value;

        $this->setAttribute('value', $value);
    }

    public function selected($selected = true) {
        if($selected) {
            $this->setAttribute('checked', null);
        } else if(!$selected && $this->hasAttribute('checked')) {
            $this->deleteAttribute('checked');
        }

        parent::selected($selected);

        return $this;
    }

    public function validation()
    {
        $value = $this->getPostValue();

        if($this->form->repeat) {
            $values = [];
            // Iterate over the column for the current $input
            $n = 0;
            foreach($value as $v) {
                if(!isset($values[$n])) {
                    $values[] = [];
                }

                if($v !== null) {
                    $v = $this->value;
                } else {
                    $v = false;
                }

                $values[$n] = $v;

                $n++;
            }

            return $values;
        }

        if($value !== null) {
            $value = $this->value;
        } else {
            $value = false;
        }

        if(!$this->selected && $value !== false) {
            $this->selected(true);
        }
        else if($this->selected && $value === false) {
            $this->selected(false);
        }

        return $value;
    }
}