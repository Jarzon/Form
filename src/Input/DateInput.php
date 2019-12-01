<?php
namespace Jarzon\Input;

use Jarzon\Input;

class DateInput extends Input
{
    public $min = null;
    public $max = null;

    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'date');
    }

    public function min(string $min)
    {
        $this->setAttribute('min', $min);

        $this->min = $min;
    }

    public function max(string $max)
    {
        $this->setAttribute('max', $max);

        $this->max = $max;
    }

    public function passValidation($value = null): bool
    {
        if(preg_match('/[0-9]{4}-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])/', $value) == 0) {
            throw new \Jarzon\ValidationException("{$this->name} is not a valid date");
        }

        $date = $this->convertDate($value);
        if($this->max !== null && $date > $this->convertDate($this->max)) {
            throw new \Jarzon\ValidationException("{$this->name} is higher that {$this->max}");
        }
        else if($this->min !== null && $date < $this->convertDate($this->min)) {
            throw new \Jarzon\ValidationException("{$this->name} is lower that {$this->min}");
        }

        return true;
    }

    protected function convertDate($date) {
        return strtotime($date);
    }
}
