<?php
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

    public function id(string $id)
    {
        $this->setAttribute('id', $id);
    }

    public function method(string $method)
    {
        $this->setAttribute('method', $method);
    }

    public function action(string $url)
    {
        $this->setAttribute('action', $url);
    }

    public function target(string $target)
    {
        $this->setAttribute('target', $target);
    }
}
