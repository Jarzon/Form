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

    public function method(string $method)
    {
        $this->setAttribute('method', $method);
    }
}