<?php
namespace Jarzon;

class FormTag extends Tag
{
    public function __construct($endingTag = false)
    {
        $tag = 'form';

        if($endingTag) {
            $tag = "/$tag";
        }

        $this->setTag($tag);
    }
}