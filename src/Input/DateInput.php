<?php declare(strict_types=1);
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

    function validateDate($date, $format = 'Y-m-d'): bool
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
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
        if(!parent::passValidation($value)) {
            return false;
        }

        if(!$this->validateDate($value)) {
            throw new \Jarzon\ValidationException("{$this->name} is not a valid date", 50);
        }

        $date = $this->convertDate($value);
        if($this->max !== null && $date > $this->convertDate($this->max)) {
            throw new \Jarzon\ValidationException("{$this->name} is higher that {$this->max}", 51);
        }
        else if($this->min !== null && $date < $this->convertDate($this->min)) {
            throw new \Jarzon\ValidationException("{$this->name} is lower that {$this->min}", 52);
        }

        return true;
    }

    protected function convertDate($date): int
    {
        return strtotime($date);
    }
}
