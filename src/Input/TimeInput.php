<?php declare(strict_types=1);
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

    public function pattern(string|null $pattern = null, string|null $message = null): static
    {
        if($pattern === null) {
            $pattern = '[0-9]{2}:[0-9]{2}';
        }

        parent::pattern($pattern, $message);

        return $this;
    }

    public function passValidation($value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        if($this->pattern !== null) {
            $format = str_replace('/', '\/', $this->pattern);
            if(preg_match("/$format/", $value) == 0) {
                throw new \Jarzon\ValidationException("{$this->name} is not a valid time", 60);
            }
        }

        return true;
    }
}
