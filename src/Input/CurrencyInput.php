<?php
namespace Jarzon\Input;

use Jarzon\DigitBasedInput;

class CurrencyInput extends DigitBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'number');
        $this->setAttribute('step', 0.01);
    }

    public function validation()
    {
        $value = parent::validation();

        if($this->form->repeat) {
            foreach ($value as $i => $v) {
                $value[$i] = $v;
            }

            return $value;
        }

        return $value;
    }
}
