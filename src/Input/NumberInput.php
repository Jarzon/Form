<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class NumberInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 1);
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

    public function validation(): array|int|null
    {
        $value = parent::validation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = (int)$v;
            }

            return $value;
        }

        if($value !== null) return (int)$value;
        else return null;
    }
}
