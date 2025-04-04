<?php declare(strict_types=1);
namespace Jarzon;

use Jarzon\Input\CheckboxInput;
use Jarzon\Input\ColorInput;
use Jarzon\Input\CurrencyInput;
use Jarzon\Input\DateInput;
use Jarzon\Input\EmailInput;
use Jarzon\Input\FloatInput;
use Jarzon\Input\HiddenInput;
use Jarzon\Input\NumberInput;
use Jarzon\Input\PasswordInput;
use Jarzon\Input\RadioInput;
use Jarzon\Input\RangeInput;
use Jarzon\Input\SearchInput;
use Jarzon\Input\SelectInput;
use Jarzon\Input\SubmitInput;
use Jarzon\Input\TelInput;
use Jarzon\Input\TextareaInput;
use Jarzon\Input\TextInput;
use Jarzon\Input\TimeInput;
use Jarzon\Input\UrlInput;

/**
 * @property string|null $row
 * @property string|null $label
 * @property string|int|float|null $value
 */
class Input extends Tag
{
    protected Form $form;

    protected string $name = '';
    protected $value = null;
    protected array $postValues = [];
    /** @var null|string|array */
    protected $postValue = null;
    protected string|null $label = null;
    protected bool $isRequired = false;
    protected bool $isMandatory = false;
    protected bool $isDisabled = false;
    protected bool $isReadonly = false;
    protected string|null $labelHtml = null;
    public string|null $class = null;

    protected $min = null;
    protected $max = null;

    protected string|null $pattern = null;

    protected bool $isLabelGenerated = false;

    public function __construct(string $name, Form $form)
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

    public function setName(string $name): void
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

    public function class(string|null $classes = null): static
    {
        if($classes === null) {
            $classes = $this->name;
        }

        $this->class = $classes;

        $this->setAttribute('class', $classes);

        return $this;
    }

    public function id(string|null $id = null): static
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

    public function getRow(): string
    {
        return $this->getLabel().$this->getHtml();
    }

    public function getLabel(): string|null
    {
        if(!$this->isLabelGenerated()) $this->generateLabel();
        return $this->labelHtml;
    }

    public function label($label = null): static
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

    public function value($value = ''): static
    {
        $this->value = $value;

        $this->setAttribute('value', is_string($value) && $value? htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE) : $value);

        return $this;
    }

    public function placeholder(string|null $placeholder = null): static
    {
        $this->setAttribute('placeholder', $placeholder);

        return $this;
    }

    public function spellcheck(bool|null $placeholder = null): static
    {
        $this->setAttribute('spellcheck', ($placeholder) ? 'true': 'false');

        return $this;
    }

    public function autocomplete(string|null $value = null): static
    {
        $this->setAttribute('autocomplete', $value);

        return $this;
    }

    public function tabindex(int|null $index = null): static
    {
        $this->setAttribute('tabindex', $index);

        return $this;
    }

    public function pattern(string|null $pattern = null, string|null $message = null): static
    {
        $this->pattern = $pattern;

        $this->setAttribute('pattern', $pattern);
        if($message !== null) {
            $this->setAttribute('title', $message);
        }

        return $this;
    }

    public function required(bool $required = true): static
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

    public function mandatory(bool $mandatory = true): static
    {
        $this->isMandatory = $mandatory;

        return $this;
    }

    public function disabled(bool $disabled = true): static
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

    public function readonly(bool $readonly = true): static
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
            throw new ValidationException("{$this->name} is required", 1);
        }
        return $value !== '';
    }

    public function inputValidation(): mixed
    {
        if($this->isDisabled) return null;
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

        if($updated || ($this->isRequired && !$this->form->update) || $this->isMandatory) {
            return $this->postValue;
        }

        return null;
    }

    public function validation(): mixed
    {
        return $this->form->validation();
    }

    public function __call($name, $arguments): void
    {
        throw new \Exception("Illegal $name attribute on $this->name");
    }

    public function submit(string $name = 'save'): SubmitInput
    {
        return $this->form->submit($name);
    }

    public function hidden(string $name): HiddenInput
    {
        return $this->form->hidden($name);
    }

    public function text(string $name): TextInput
    {
        return $this->form->text($name);
    }

    public function textarea(string $name): TextareaInput
    {
        return $this->form->textarea($name);
    }

    public function password(string $name): PasswordInput
    {
        return $this->form->password($name);
    }

    public function email(string $name): EmailInput
    {
        return $this->form->email($name);
    }

    public function url(string $name): UrlInput
    {
        return $this->form->url($name);
    }

    public function search(string $name): SearchInput
    {
        return $this->form->search($name);
    }

    public function tel(string $name): TelInput
    {
        return $this->form->tel($name);
    }

    public function color(string $name): ColorInput
    {
        return $this->form->color($name);
    }
    public function number(string $name): NumberInput
    {
        return $this->form->number($name);
    }

    public function float(string $name): FloatInput
    {
        return $this->form->float($name);
    }

    public function currency(string $name): CurrencyInput
    {
        return $this->form->currency($name);
    }

    public function range(string $name): RangeInput
    {
        return $this->form->range($name);
    }

    public function date(string $name): DateInput
    {
        return $this->form->date($name);
    }

    public function time(string $name): TimeInput
    {
        return $this->form->time($name);
    }

    public function select(string $name): SelectInput
    {
        return $this->form->select($name);
    }

    public function radio(string $name): RadioInput
    {
        return $this->form->radio($name);
    }

    public function checkbox(string $name): CheckboxInput
    {
        return $this->form->checkbox($name);
    }
}
