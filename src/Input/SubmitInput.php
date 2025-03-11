<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Form;
use Jarzon\Input;

class SubmitInput extends Input
{
    public function __construct(string|null $name = null, Form $form)
    {
        $this->setAttribute('type', 'submit');
        parent::__construct($name, $form);
    }

    public function inputValidation(): array|string|null
    {
        return null;
    }
}
