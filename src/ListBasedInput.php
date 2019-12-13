<?php
namespace Jarzon;

class ListBasedInput extends Input
{
    protected $selected;

    public function selected($selected)
    {
        $this->selected = $selected;

        $this->resetIsHtmlGenerated();
    }

    public function getSelected()
    {
        return $this->selected;
    }

    public function value($value = ''): Input
    {
        $this->selected($value);

        return $this;
    }

    public function passValidation($value = null): bool
    {
        return true;
    }
}
