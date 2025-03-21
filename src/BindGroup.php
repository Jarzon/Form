<?php declare(strict_types=1);
namespace Jarzon;

class BindGroup extends Tag
{
    public array $bindValues = [];
    public string $bindOptionText = 'text';
    public array $bindOptionAttributes = ['value' => 'value'];

    public function __construct(string $class = '') {
        if($class !== '') {
            $this->setAttribute('class', $class);
        }
    }

    public function bindOptionText(string $name): void
    {
        $this->bindOptionText = $name;
    }

    public function bindOptionValue(string $name): void
    {
        $this->bindOptionAttribute('value', $name);
    }

    public function bindOptionAttribute(string $attribute, string $name): void
    {
        $this->bindOptionAttributes[$attribute] = $name;
    }

    public function bindValues(array $values): void
    {
        $this->bindValues = $values;
    }
}
