<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class DateInput extends TextBasedInput
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setAttribute('type', 'date');
    }

    public function pattern(?string $pattern = null)
    {
        if($pattern === null) {
            $pattern = '(0?[1-9]|[12][0-9]|3[01])[- /.](0?[1-9]|1[012])[- /.](19|20)\d\d';
        }

        parent::pattern($pattern);

        return $this;
    }

    public function passValidation($value = null): bool
    {
        parent::passValidation($value);

        if($this->pattern !== null) {
            $format = str_replace('/', '\/', $this->pattern);
            if(preg_match("/$format/", $value) == 0) {
                throw new \Exception("{$this->name} is not a valid date");
            }
        }

        return true;
    }
}