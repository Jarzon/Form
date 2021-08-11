<?php declare(strict_types=1);
namespace Jarzon;

class Option
{
    public string $text;
    public array $attr;

    public function __construct(string $text, array $attr = [])
    {
        $this->text = $text;
        $this->attr = $attr;
    }
}
