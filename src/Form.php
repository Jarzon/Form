<?php
namespace Jarzon;

use Jarzon\Input\{
    CheckboxInput,
    ColorInput,
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
    protected $post = [];
    protected $update = false;

    /** @var $lastRow Input */
    protected $lastRow;

    public function __construct(array $post)
    {
        $this->post = $post;

        $this->addInput(new FormTag(), 'form');
    }

    public function __invoke(string $name)
    {
        if($this->keyExists($name)) {
            return $this->getInput($name);
        }

        return null;
    }

    public function submitted() : bool
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

    public function getForms() : array
    {
        return $this->inputs;
    }

    public function validation()
    {
        $values = [];

        foreach($this->inputs as $key => $input) {
            if(!is_subclass_of($input, 'Jarzon\Input')) {
                continue;
            }

            $value = null;

            if($this->keyExists($key) && isset($this->post[$key])) {
                $value = $this->post[$key];
            }

            $result = $input->validation($value, $this->update);

            if($result !== null) {
                $values[$key] = $result;
            }
        }

        return $values;
    }

    /*
     * Collection methods
     */

    protected function addInput(object $object, ?string $key = null, bool $lastRow = true)
    {
        if ($key === null) {
            $this->inputs[] = $object;
            return;
        }

        if (isset($this->inputs[$key])) {
            throw new \Exception("Key $key already in use.");
        }

        $this->inputs[$key] = $object;

        if($lastRow) $this->lastRow =& $this->inputs[$key];
    }

    public function deleteInput(string $key)
    {
        if (!isset($this->inputs[$key]))
        {
            throw new \Exception("Invalid key $key.");
        }

        unset($this->inputs[$key]);
    }

    public function keyExists(string $key) : bool
    {
        return isset($this->inputs[$key]);
    }

    public function getInput($key)
    {
        if (!isset($this->inputs[$key])) {
            throw new \Exception("Invalid key $key.");
        }

        return $this->inputs[$key];
    }

    public function keys() : array
    {
        return array_keys($this->inputs);
    }

    public function length() : int
    {
        return count($this->inputs);
    }

    /*
     * Input types
     */

    public function submit(string $name = null)
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

    public function hidden(string $name)
    {
        $this->addInput(new HiddenInput($name), $name);

        return $this;
    }

    public function text(string $name)
    {
        $this->addInput(new TextInput($name), $name);

        return $this;
    }

    public function textarea(string $name)
    {
        $this->addInput(new TextareaInput($name), $name);

        return $this;
    }

    public function password(string $name)
    {
        $this->addInput(new PasswordInput($name), $name);

        return $this;
    }

    public function email(string $name)
    {
        $this->addInput(new EmailInput($name), $name);

        return $this;
    }

    public function url(string $name)
    {
        $this->addInput(new UrlInput($name), $name);

        return $this;
    }

    public function search(string $name)
    {
        $this->addInput(new SearchInput($name), $name);

        return $this;
    }

    public function tel(string $name)
    {
        $this->addInput(new TelInput($name), $name);

        return $this;
    }

    public function color(string $name)
    {
        $this->addInput(new ColorInput($name), $name);

        return $this;
    }

    public function number(string $name)
    {
        $this->addInput(new NumberInput($name), $name);

        return $this;
    }

    public function float(string $name)
    {
        $this->addInput(new FloatInput($name), $name);

        return $this;
    }

    public function range(string $name)
    {
        $this->addInput(new RangeInput($name), $name);

        return $this;
    }

    public function date(string $name)
    {
        $this->addInput(new DateInput($name), $name);

        return $this;
    }

    public function time(string $name)
    {
        $this->addInput(new TimeInput($name), $name);

        return $this;
    }

    public function select(string $name)
    {
        $this->addInput(new SelectInput($name), $name);

        return $this;
    }

    public function radio(string $name)
    {
        $this->addInput(new RadioInput($name), $name);

        return $this;
    }

    public function checkbox(string $name)
    {
        $this->addInput(new CheckboxInput($name), $name);

        return $this;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        $this->addInput(new FileInput($name, $destination, $ext), $name);

        $this->getInput('form')->setAttribute('enctype', 'multipart/form-data');

        return $this;
    }

    /*
     * Input attributes
     */

    public function method(string $method)
    {
        $this->getInput('form')->setAttribute('method', $method);

        return $this;
    }

    public function action(string $url)
    {
        $this->getInput('form')->setAttribute('action', $url);

        return $this;
    }

    public function required(bool $required = true)
    {
        $this->lastRow->required($required);

        return $this;
    }

    public function value($value = '')
    {
        $this->lastRow->value($value);

        return $this;
    }

    public function class(?string $classes = null)
    {
        $this->lastRow->class($classes);

        return $this;
    }

    public function id(?string $id = null)
    {
        $this->lastRow->id($id);

        return $this;
    }

    public function min(int $min = 0)
    {
        $this->lastRow->min($min);

        return $this;
    }

    public function max(int $max = PHP_INT_MAX)
    {
        $this->lastRow->max($max);

        return $this;
    }

    public function accept(array $types = [])
    {
        $this->lastRow->accept($types);

        return $this;
    }

    public function selected($selected = true)
    {
        $this->lastRow->selected($selected);

        return $this;
    }

    public function multiple(bool $multiple = true)
    {
        $this->lastRow->multiple($multiple);

        return $this;
    }

    public function pattern(?string $pattern = null)
    {
        $this->lastRow->pattern($pattern);

        return $this;
    }

    public function placeholder(?string $placeholder = null)
    {
        $this->lastRow->placeholder($placeholder);

        return $this;
    }

    public function spellcheck(?bool $placeholder = null)
    {
        $this->lastRow->spellcheck($placeholder);

        return $this;
    }

    public function autocomplete(?string $value = null)
    {
        $this->lastRow->autocomplete($value);

        return $this;
    }

    public function tabindex(?int $index = null)
    {
        $this->lastRow->tabindex($index);

        return $this;
    }

    public function label($label = null)
    {
        $this->lastRow->label($label);

        return $this;
    }

    public function attributes($attributes = [])
    {
        foreach ($attributes as $name => $value) {
            $this->lastRow->setAttribute($name, $value);
        }

        return $this;
    }

    public function deleteAttribute($attribute)
    {
        $this->lastRow->deleteAttribute($attribute);

        return $this;
    }
}