<?php declare(strict_types=1);
namespace Jarzon;

/**
 * @property ?string $row
 * @property ?string $label
 * @property null|string|int|float $value
 */
class Input extends Tag
{
    /** @var Form */
    protected $form;

    public string $name = '';
    protected $value = null;
    protected array $postValues = [];
    /** @var null|string|array */
    protected $postValue = null;
    protected ?string $label = null;
    protected bool $isRequired = false;
    protected bool $isDisabled = false;
    protected bool $isReadonly = false;
    protected ?string $labelHtml = null;
    public ?string $class = null;

    protected $min = null;
    protected $max = null;

    protected ?string $pattern = null;

    protected bool $isLabelGenerated = false;

    public function __construct(string $name, $form)
    {
        $this->form = $form;

        $this->setTag('input');
        $this->setName($name);
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if(method_exists($this, $method)) {
            return $this->$method();
        }
    }

    public function setName(string $name)
    {
        $this->setAttribute('name', $name);

        if($this->form->repeat) {
            $name = str_replace('[]', '', $name);
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function class(?string $classes = null): Input
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->class = $classes;

        $this->setAttribute('class', $classes);

        return $this;
    }

    public function id(?string $id = null): Input
    {
        if($id === null) $id = $this->name;

        $this->setAttribute('id', $id);

        return $this;
    }

    public function generateLabel(): string
    {
        $label = '';
        if($this->label !== null) {
            $this->labelHtml = $this->generateTag('label', ['for' => $this->name], $this->label);
        }

        $this->isLabelGenerated = true;

        return $label;
    }

    public function getRow()
    {
        return $this->getLabel().$this->getHtml();
    }

    public function getLabel()
    {
        if(!$this->isLabelGenerated()) $this->generateLabel();
        return $this->labelHtml;
    }

    public function label($label = null): Input
    {
        if($label !== null) {
            $this->id();
        }

        $this->label = $label;

        $this->resetIsLabelGenerated();

        return $this;
    }

    public function isLabelGenerated(): bool
    {
        return $this->isLabelGenerated;
    }

    public function resetIsLabelGenerated(): void
    {
        $this->isLabelGenerated = false;
    }

    public function value($value = ''): Input
    {
        $this->value = $value;

        $this->setAttribute('value', is_string($value)? htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE) : $value);

        return $this;
    }

    public function placeholder(?string $placeholder = null): Input
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function spellcheck(?bool $placeholder = null): Input
    {
        $this->setAttribute('spellcheck', ($placeholder) ? 'true': 'false');

        return $this;
    }

    public function autocomplete(?string $value = null): Input
    {
        $this->setAttribute('autocomplete', $value);

        return $this;
    }

    public function tabindex(?int $index = null): Input
    {
        $this->setAttribute('tabindex', $index);

        return $this;
    }

    public function pattern(?string $pattern = null, ?string $message = null): Input
    {
        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);
        if($message !== null) {
            $this->setAttribute('title', $message);
        }

        return $this;
    }

    public function required(bool $required = true): Input
    {
        if($required && !$this->isRequired) {
            $this->setAttribute('required', null);
        }
        else if(!$required && $this->isRequired) {
            $this->deleteAttribute('required');
        }

        $this->isRequired = $required;

        return $this;
    }

    public function disabled(bool $disabled = true): Input
    {
        if($disabled && !$this->isDisabled) {
            $this->setAttribute('disabled', null);
        }
        else if(!$disabled && $this->isDisabled) {
            $this->deleteAttribute('disabled');
        }

        $this->isDisabled = $disabled;

        return $this;
    }

    public function readonly(bool $readonly = true): Input
    {
        if($readonly && !$this->isReadonly) {
            $this->setAttribute('readonly', null);
        }
        else if(!$readonly && $this->isReadonly) {
            $this->deleteAttribute('readonly');
        }

        $this->isReadonly = $readonly;

        return $this;
    }

    public function isUpdated($value): bool
    {
        return $value != $this->value || ($this->value !== null && !$this->form->update);
    }

    public function getPostValue()
    {
        return $this->form->post[$this->name]?? null;
    }

    public function processValues(): void
    {
        $value = $this->getPostValue();

        if($this->form->repeat) {
            $this->postValues = (array)$value;
            return;
        }

        $this->postValue = $value;
    }

    protected function passValidation($value): bool
    {
        if($value == '' && $this->isRequired) {
            throw new ValidationException("{$this->name} is required");
        }
        return $value !== '';
    }

    public function validation()
    {
        if($this->form->repeat) {
            foreach($this->postValues as $value) {
                $this->passValidation($value);
            }

            return $this->postValues;
        }

        $this->passValidation($this->postValue);

        $updated = $this->isUpdated($this->postValue);

        if($updated) {
            $this->value($this->postValue);
        }

        if($updated || ($this->isRequired && !$this->form->update)) {
            return $this->postValue;
        }

        return null;
    }

    public function __call($name, $arguments)
    {
        throw new \Exception("Illegal $name attribute on $this->name");
    }
}
