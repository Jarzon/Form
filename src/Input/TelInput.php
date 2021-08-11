<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\TextBasedInput;

class TelInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'tel');
        $this->pattern();
    }

    public function pattern(?string $pattern = null, ?string $message = null): Input
    {
        if ($pattern === null) {
            $pattern = '(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?';
        }

        if ($message === null) {
            $message = 'Phone number (eg. 418-555-5555, 1-418-555-5555 #555)';
        }

        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);
        $this->setAttribute('title', $message);

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
                throw new \Jarzon\ValidationException("{$this->name} is not a valid phone number");
            }
        }

        return true;
    }
}
