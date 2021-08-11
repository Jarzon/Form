<?php declare(strict_types=1);
namespace Jarzon;

class FormTag extends Tag
{
    public function __construct($endingTag = false)
    {
        $tag = 'form';

        if($endingTag) {
            $tag = "/$tag";
        } else {
            $this->method('POST');
        }

        $this->setTag($tag);
    }

    public function id(string $id): FormTag
    {
        $this->setAttribute('id', $id);

        return $this;
    }

    public function method(string $method): FormTag
    {
        $this->setAttribute('method', $method);

        return $this;
    }

    public function action(string $url): FormTag
    {
        $this->setAttribute('action', $url);

        return $this;
    }

    public function target(string $target): FormTag
    {
        $this->setAttribute('target', $target);

        return $this;
    }
}
