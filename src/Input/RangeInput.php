<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class RangeInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'range');
    }

    public function min(int $min = 0): static
    {
        parent::min($min);
        $this->setAttribute('min', $min);

        return $this;
    }

    public function max(int $max = PHP_INT_MAX): static
    {
        parent::max($max);
        $this->setAttribute('max', $max);

        return $this;
    }
}
