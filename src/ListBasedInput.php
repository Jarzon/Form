<?php
namespace Jarzon;

class ListBasedInput extends Input
{
    protected $values = [];
    protected $selected = [];


    public function selected($selected) {
        $this->selected = $selected;
    }

    public function value($values = [])
    {
        $this->values = $values;
    }

    public function passValidation($value = null) : bool
    {


        return true;
    }
}