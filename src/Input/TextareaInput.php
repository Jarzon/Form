<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\TextBasedInput;
use Jarzon\ValidationException;

class TextareaInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setTag('textarea');
    }

    public function value($value = ''): static
    {
        $this->value = $value;

        return $this;
    }

    public function generateHtml(): void
    {
        $this->setHtml($this->generateTag($this->tag, $this->attributes, $this->value));
    }

    public function passValidation($value = ''): bool
    {
        if($value == '' && $this->isRequired) {
            throw new ValidationException("{$this->name} is required", 1);
        }

        $value = (string)$value;

        $numberChars = mb_strlen($value);
        $lineBreaks = $this->max > 0? mb_substr_count($value, "\n") : 0;
        if(!empty($this->max) && (($numberChars - $lineBreaks) > $this->max || $lineBreaks > $this->max)) {
            throw new ValidationException("{$this->name} is too long", 20);
        }
        else if(!empty($this->min) && ($numberChars - $lineBreaks) < $this->min) {
            throw new ValidationException("{$this->name} is too short", 21);
        }

        return $value !== '';
    }
}
