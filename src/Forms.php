<?php
namespace Jarzon;

use Jarzon\Input\{
    CheckboxInput, ColorInput, DateInput, EmailInput, FileInput, FloatInput,
    HiddenInput, NumberInput, PasswordInput, RadioInput, RangeInput, SearchInput,
    SelectInput, TelInput, TextareaInput, TextInput, TimeInput, UrlInput
};

class Forms
{
    public $forms = [];
    protected $items = [];
    protected $post = [];
    protected $update = false;

    /** @var $lastRow Input */
    protected $lastRow;

    public function __construct(array $post)
    {
        $this->post = $post;
    }

    public function updateValues($values = []) {
        if(empty($values)) {
            $values = $this->post;
        } else {
            $this->update = true;
        }

        foreach ($values as $name => $value) {
            if($this->keyExists($name)) {
                $this->getItem($name)->value($value);
            }
        }
    }

    public function getForms() : array
    {
        $this->generateInputs();

        return $this->items;
    }

    public function generateInputs()
    {
        foreach ($this->items as $form) {
            $form->generateInput();
        }
    }

    public function validation()
    {
        $values = [];

        foreach($this->items as $key => $input) {
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

    protected function addItem(object $object, ?string $key = null)
    {
        if ($key === null) {
            $this->items[] = $object;
            return;
        }

        if (isset($this->items[$key])) {
            throw new \Exception("Key $key already in use.");
        }

        $this->items[$key] = $object;

        $this->lastRow =& $this->items[$key];
    }

    public function deleteItem(string $key)
    {
        if (!isset($this->items[$key]))
        {
            throw new \Exception("Invalid key $key.");
        }

        unset($this->items[$key]);
    }

    public function keyExists(string $key) : bool
    {
        return isset($this->items[$key]);
    }

    public function getItem($key)
    {
        if (!isset($this->items[$key])) {
            throw new \Exception("Invalid key $key.");
        }

        return $this->items[$key];
    }

    public function keys() : array
    {
        return array_keys($this->items);
    }

    public function length() : int
    {
        return count($this->items);
    }

    /*
     * Input types
     */

    public function hidden(string $name)
    {
        $this->addItem(new HiddenInput($name), $name);

        return $this;
    }

    public function text(string $name)
    {
        $this->addItem(new TextInput($name), $name);

        return $this;
    }

    public function textarea(string $name)
    {
        $this->addItem(new TextareaInput($name), $name);

        return $this;
    }

    public function password(string $name)
    {
        $this->addItem(new PasswordInput($name), $name);

        return $this;
    }

    public function email(string $name)
    {
        $this->addItem(new EmailInput($name), $name);

        return $this;
    }

    public function url(string $name)
    {
        $this->addItem(new UrlInput($name), $name);

        return $this;
    }

    public function search(string $name)
    {
        $this->addItem(new SearchInput($name), $name);

        return $this;
    }

    public function tel(string $name)
    {
        $this->addItem(new TelInput($name), $name);

        return $this;
    }

    public function color(string $name)
    {
        $this->addItem(new ColorInput($name), $name);

        return $this;
    }

    public function number(string $name)
    {
        $this->addItem(new NumberInput($name), $name);

        return $this;
    }

    public function float(string $name)
    {
        $this->addItem(new FloatInput($name), $name);

        return $this;
    }

    public function range(string $name)
    {
        $this->addItem(new RangeInput($name), $name);

        return $this;
    }

    public function date(string $name)
    {
        $this->addItem(new DateInput($name), $name);

        return $this;
    }

    public function time(string $name)
    {
        $this->addItem(new TimeInput($name), $name);

        return $this;
    }

    public function select(string $name)
    {
        $this->addItem(new SelectInput($name), $name);

        return $this;
    }

    public function radio(string $name)
    {
        $this->addItem(new RadioInput($name), $name);

        return $this;
    }

    public function checkbox(string $name)
    {
        $this->addItem(new CheckboxInput($name), $name);

        return $this;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        $this->addItem(new FileInput($name, $destination, $ext), $name);

        return $this;
    }

    /*
     * Input attributes
     */

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