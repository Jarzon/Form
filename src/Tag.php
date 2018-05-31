<?php
namespace Jarzon;

class Tag
{
    public $tag = '';
    protected $attributes = [];
    public $html = '';

    public function generateTag(string $tag, array $attributes, $content = false) : string
    {
        $attr = '';

        foreach($attributes as $attribute => $value) {
            if($value === null) {
                $attr .= " $attribute";
            } else {
                $attr .= " $attribute=\"$value\"";
            }
        }

        $html = "<$tag$attr>";

        if($content !== false) {
            $html .= "$content</$tag>";
        }

        return $html;
    }

    public function setTag(string $tag)
    {
        $this->tag = $tag;
    }

    public function generateHtml()
    {
        $this->setHtml($this->generateTag($this->tag, $this->attributes));
    }

    public function setAttribute(string $name, $value = null)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    public function deleteAttribute(string $name)
    {
        if(!$this->hasAttribute($name)) {
            throw new \Exception("Trying to delete input attribute $name and it doesn't exist");
        }
        unset($this->attributes[$name]);
    }

    public function hasAttribute(string $name) : bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getHtml() : string
    {
        return $this->html;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }
}