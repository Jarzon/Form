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

    public function min(int $min = 0)
    {
        parent::min($min);
        $this->setAttribute('min', $min);
    }

    public function max(int $max = PHP_INT_MAX)
    {
        parent::max($max);
        $this->setAttribute('max', $max);
    }
}
