<?php declare(strict_types=1);
namespace Jarzon;

/**
 * @property null|string|array $html
 * @property string $row
 */
class Tag
{
    protected string $tag = '';
    protected array $attributes = [];
    protected $html = null;

    protected bool $isHtmlGenerated = false;

    public function __get($name)
    {
        if($name === 'html' || $name === 'row') {
            return $this->getHtml();
        }
    }

    public function generateTag(string $tag, array $attributes, $content = false): string
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

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

    public function generateHtml(): void
    {
        $this->setHtml($this->generateTag($this->tag, $this->attributes));
        $this->isHtmlGenerated = true;
    }

    public function setAttribute(string $name, $value = null): Tag
    {
        $this->attributes[$name] = $value;
        $this->resetIsHtmlGenerated();

        return $this;
    }

    public function getAttribute(string $name)
    {
        return $this->attributes[$name];
    }

    public function deleteAttribute(string $name): void
    {
        if(!$this->hasAttribute($name)) {
            throw new \Exception("Trying to delete input attribute $name and it doesn't exist");
        }
        unset($this->attributes[$name]);
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    public function getHtml()
    {
        if(!$this->isHtmlGenerated()) $this->generateHtml();
        return $this->html;
    }

    public function setHtml($html): void
    {
        $this->html = $html;
    }

    public function isHtmlGenerated()
    {
        return $this->isHtmlGenerated;
    }

    public function resetIsHtmlGenerated(): void
    {
        $this->isHtmlGenerated = false;
    }

    public function attributes($attributes = []): self
    {
        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }

        return $this;
    }
}
