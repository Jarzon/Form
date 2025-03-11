<?php declare(strict_types=1);
namespace Jarzon;

class ListBasedInput extends Input
{
    protected $selected;

    public function selected($selected): static
    {
        $this->selected = $selected;

        $this->resetIsHtmlGenerated();

        return $this;
    }

    public function getSelected()
    {
        return $this->selected;
    }

    public function value($value = ''): static
    {
        $this->selected($value);

        return $this;
    }
}
