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
