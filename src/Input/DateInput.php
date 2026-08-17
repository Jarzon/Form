<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\ValidationException;

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

    public function min(string $min): static
    {
        $this->setAttribute('min', $min);

        $this->min = $min;

        return $this;
    }

    public function max(string $max): static
    {
        $this->setAttribute('max', $max);

        $this->max = $max;

        return $this;
    }

    public function passValidation($value = null): ValidationException|bool
    {
        $err = parent::passValidation($value);
        if($err) return $err;

        if(!$this->validateDate($value)) {
            return new ValidationException("{$this->name} is not a valid date", 50);
        }

        $date = $this->convertDate($value);
        if($this->max !== null && $date > $this->convertDate($this->max)) {
            return new ValidationException("{$this->name} is higher that {$this->max}", 51);
        }
        else if($this->min !== null && $date < $this->convertDate($this->min)) {
            return new ValidationException("{$this->name} is lower that {$this->min}", 52);
        }

        return false;
    }

    protected function convertDate($date): int
    {
        return strtotime($date);
    }
}
