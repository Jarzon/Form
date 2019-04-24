<?php
namespace Jarzon;

class ListBasedInput extends Input
{
    protected $values = [];
    protected $selected = [];

    public function __get($name)
    {
        $return = parent::__get($name);
        if($return) {
            return $return;
        }
        else if($name === 'selected') {
            return $this->getSelected();
        }
    }

    public function selected($selected)
    {
        $this->selected = $selected;

        $this->resetIsHtmlGenerated();
    }

    public function getSelected()
    {
        return $this->selected;
    }

    public function value($values = [])
    {
        if(is_array($values)) {
            $this->values = $values;
        } else {
            $this->selected($values);
        }
    }

    public function passValidation($value = null): bool
    {
        return true;
    }
}