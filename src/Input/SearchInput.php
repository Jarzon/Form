<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class SearchInput extends TextBasedInput
{
    public function __construct(string $name, $form)
    {
        parent::__construct($name, $form);
        $this->setAttribute('type', 'search');
    }
}
