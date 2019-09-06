<?php
namespace Jarzon;

use Jarzon\Input\{
    CheckboxInput,
    ColorInput,
    CurrencyInput,
    DateInput,
    EmailInput,
    FileInput,
    FloatInput,
    HiddenInput,
    NumberInput,
    PasswordInput,
    RadioInput,
    RangeInput,
    SearchInput,
    SelectInput,
    SubmitInput,
    TelInput,
    TextareaInput,
    TextInput,
    TimeInput,
    UrlInput
};

class Form
{
    protected $inputs = [];
    public $post = [];
    public $files = [];
    public $update = false;
    public $repeat = false;

    /** @var $lastRow Input */
    protected $lastRow;

    public function __construct(array $post, array $files = [])
    {
        $this->post = $post;
        $this->files = $files;

        $this->addInput(new FormTag(), 'form');
    }

    public function __invoke(string $name)
    {
        if($this->keyExists($name)) {
            return $this->getInput($name);
        }

        return null;
    }

    public function submitted(): bool
    {
        return array_key_exists('submit', $this->post);
    }

    public function updateValues($values = [])
    {
        if(empty($values)) {
            $values = $this->post;
        } else {
            $this->update = true;
        }

        foreach ($values as $name => $value) {
            if($this->keyExists($name)) {
                $this->getInput($name)->value($value);
            }
        }
    }

    public function getForms(): array
    {
        return $this->inputs;
    }

    public function validation(): array
    {
        $values = [];

        foreach($this->inputs as $key => $input) {
            if(!is_subclass_of($input, 'Jarzon\Input') || !$this->keyExists($key)) {
                continue;
            }

            $result = $input->validation();

            if($this->repeat) {
                foreach ($result as $i => $v) {
                    if(!isset($values[$i])) {
                        $values[$i] = [];
                    }

                    $values[$i][$key] = $v;
                }

                continue;
            }

            if($result !== null) {
                $values[$key] = $result;
            }
        }

        return $values;
    }

    /*
     * Collection methods
     */

    protected function addInput(object $object, ?string $key = null, bool $lastRow = true): void
    {
        if ($key === null) {
            $this->inputs[] = $object;
            return;
        }

        if (isset($this->inputs[$key])) {
            throw new \Exception("Key $key already in use.");
        }

        if($this->repeat) {
            $key = str_replace('[]', '', $key);
        }

        $this->inputs[$key] = $object;

        if($lastRow) $this->lastRow =& $this->inputs[$key];
    }

    public function deleteInput(string $key): Form
    {
        if (!isset($this->inputs[$key]))
        {
            throw new \Exception("Invalid key $key.");
        }

        unset($this->inputs[$key]);

        return $this;
    }

    public function keyExists(string $key) : bool
    {
        return isset($this->inputs[$key]);
    }

    public function getInput($key): Tag
    {
        if (!isset($this->inputs[$key])) {
            throw new \Exception("Invalid key $key.");
        }

        return $this->inputs[$key];
    }

    public function keys(): array
    {
        return array_keys($this->inputs);
    }

    public function length(): int
    {
        return count($this->inputs);
    }

    /*
     * Input types
     */

    public function submit(string $name = null): Form
    {
        if($name === null) {
            $name = 'submit';
        }

        if($this->keyExists('submit')) {
            throw new \Error('The class only support one submit button');
        }

        $this->addInput(new SubmitInput($name), 'submit');

        if($this->keyExists('form')) {
            $this->addInput(new FormTag(true), '/form', false);
        }

        return $this;
    }

    public function hidden(string $name): Form
    {
        $this->addInput(new HiddenInput($name, $this), $name);

        return $this;
    }

    public function text(string $name): Form
    {
        $this->addInput(new TextInput($name, $this), $name);

        return $this;
    }

    public function textarea(string $name): Form
    {
        $this->addInput(new TextareaInput($name, $this), $name);

        return $this;
    }

    public function password(string $name): Form
    {
        $this->addInput(new PasswordInput($name, $this), $name);

        return $this;
    }

    public function email(string $name): Form
    {
        $this->addInput(new EmailInput($name, $this), $name);

        return $this;
    }

    public function url(string $name): Form
    {
        $this->addInput(new UrlInput($name, $this), $name);

        return $this;
    }

    public function search(string $name): Form
    {
        $this->addInput(new SearchInput($name, $this), $name);

        return $this;
    }

    public function tel(string $name): Form
    {
        $this->addInput(new TelInput($name, $this), $name);

        return $this;
    }

    public function color(string $name): Form
    {
        $this->addInput(new ColorInput($name, $this), $name);

        return $this;
    }

    public function number(string $name): Form
    {
        $this->addInput(new NumberInput($name, $this), $name);

        return $this;
    }

    public function float(string $name): Form
    {
        $this->addInput(new FloatInput($name, $this), $name);

        return $this;
    }

    public function currency(string $name): Form
    {
        $this->addInput(new CurrencyInput($name, $this), $name);

        return $this;
    }

    public function range(string $name): Form
    {
        $this->addInput(new RangeInput($name, $this), $name);

        return $this;
    }

    public function date(string $name): Form
    {
        $this->addInput(new DateInput($name, $this), $name);

        return $this;
    }

    public function time(string $name): Form
    {
        $this->addInput(new TimeInput($name, $this), $name);

        return $this;
    }

    public function select(string $name): Form
    {
        $this->addInput(new SelectInput($name, $this), $name);

        return $this;
    }

    public function radio(string $name): Form
    {
        $this->addInput(new RadioInput($name, $this), $name);

        return $this;
    }

    public function checkbox(string $name): Form
    {
        $this->addInput(new CheckboxInput($name, $this), $name);

        return $this;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = ''): Form
    {
        $this->addInput(new FileInput($name, $this, $destination, $ext), $name);

        $this->getInput('form')->setAttribute('enctype', 'multipart/form-data');

        return $this;
    }

    /*
     * Input attributes
     */

    public function method(string $method): Form
    {
        $this->getInput('form')->method($method);

        return $this;
    }

    public function action(string $url): Form
    {
        $this->getInput('form')->action($url);

        return $this;
    }

    public function target(string $target): Form
    {
        $this->getInput('form')->target($target);

        return $this;
    }

    public function required(bool $required = true): Form
    {
        $this->lastRow->required($required);

        return $this;
    }

    public function value($value = ''): Form
    {
        $this->lastRow->value($value);

        return $this;
    }

    public function class(?string $classes = null): Form
    {
        $this->lastRow->class($classes);

        return $this;
    }

    public function id(?string $id = null): Form
    {
        $this->lastRow->id($id);

        return $this;
    }

    public function min(...$min): Form
    {
        $this->lastRow->min(...$min);

        return $this;
    }

    public function max(...$max): Form
    {
        $this->lastRow->max(...$max);

        return $this;
    }

    public function accept(array $types = []): Form
    {
        $this->lastRow->accept($types);

        return $this;
    }

    public function selected($selected = true): Form
    {
        $this->lastRow->selected($selected);

        return $this;
    }

    public function disabled($disabled = true): Form
    {
        $this->lastRow->disabled($disabled);

        return $this;
    }

    public function readonly($readonly = true): Form
    {
        $this->lastRow->readonly($readonly);

        return $this;
    }

    public function multiple(bool $multiple = true): Form
    {
        $this->lastRow->multiple($multiple);

        return $this;
    }

    public function pattern(?string $pattern = null, ?string $message = null): Form
    {
        $this->lastRow->pattern($pattern, $message);

        return $this;
    }

    public function placeholder(?string $placeholder = null): Form
    {
        $this->lastRow->placeholder($placeholder);

        return $this;
    }

    public function spellcheck(?bool $placeholder = null): Form
    {
        $this->lastRow->spellcheck($placeholder);

        return $this;
    }

    public function autocomplete(?string $value = null): Form
    {
        $this->lastRow->autocomplete($value);

        return $this;
    }

    public function tabindex(?int $index = null): Form
    {
        $this->lastRow->tabindex($index);

        return $this;
    }

    public function label($label = null): Form
    {
        $this->lastRow->label($label);

        return $this;
    }

    public function attributes($attributes = []): Form
    {
        foreach ($attributes as $name => $value) {
            $this->lastRow->setAttribute($name, $value);
        }

        return $this;
    }

    public function deleteAttribute($attribute): Form
    {
        $this->lastRow->deleteAttribute($attribute);

        return $this;
    }

    public function repeat(): Form
    {
        $this->repeat = true;

        return $this;
    }

    public function group(string $name, array $values): Form
    {
        $this->lastRow->group($name, $values);

        return $this;
    }
}
