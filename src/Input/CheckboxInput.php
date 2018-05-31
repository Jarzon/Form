<?php
namespace Jarzon\Input;

use Jarzon\ListBasedInput;

class CheckboxInput extends ListBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
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

        $this->selected = $selected;
    }

    public function validation($value = null, $update = false)
    {
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