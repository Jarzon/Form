<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\TextBasedInput;
use Jarzon\ValidationException;

class TimeInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'time');
    }

    public function pattern(string|null $pattern = null, string|null $message = null): static
    {
        if($pattern === null) {
            $pattern = '[0-9]{2}:[0-9]{2}';
        }

        parent::pattern($pattern, $message);

        return $this;
    }

    public function passValidation($value = null): ValidationException|bool
    {
        $err = parent::passValidation($value);
        if($err) return $err;

        if($this->pattern !== null) {
            $format = str_replace('/', '\/', $this->pattern);
            if(preg_match("/$format/", $value) == 0) {
                return new ValidationException("{$this->name} is not a valid time", 60);
            }
        }

        return false;
    }
}
