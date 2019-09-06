<?php
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\TextBasedInput;

class TimeInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'time');
    }

    public function pattern(?string $pattern = null): Input
    {
        if($pattern === null) {
            $pattern = '[0-9]{2}:[0-9]{2}';
        }

        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);

        return $this;
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if($this->pattern !== null) {
            $format = str_replace('/', '\/', $this->pattern);
            if(preg_match("/$format/", $value) == 0) {
                throw new \Jarzon\ValidationException("{$this->name} is not a valid time");
            }
        }

        return true;
    }
}
