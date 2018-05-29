<?php
namespace Jarzon;

use Jarzon\Input\ColorInput;
use Jarzon\Input\DateInput;
use Jarzon\Input\EmailInput;
use Jarzon\Input\FileInput;
use Jarzon\Input\FloatInput;
use Jarzon\Input\HiddenInput;
use Jarzon\Input\NumberInput;
use Jarzon\Input\PasswordInput;
use Jarzon\Input\RangeInput;
use Jarzon\Input\SearchInput;
use Jarzon\Input\TelInput;
use Jarzon\Input\TextareaInput;
use Jarzon\Input\TextInput;
use Jarzon\Input\UrlInput;

class Forms
{
    public $forms = [];
    protected $items = [];
    protected $post = [];
    protected $update = false;

    protected $lastRow;

    public function __construct(array $post)
    {
        $this->post = $post;
    }

    protected function row(string $type, string $name)
    {
        $row = ['type' => $type, 'name' => $name, 'attributes' => ['name' => $name], 'label' => $name, 'value' => ''];

        if(!in_array($type, ['textarea', 'select', 'radio'])) {
            $row['attributes']['type'] = $type;
        }

        $this->forms[$name] = $row;

        $this->lastRow =& $this->forms[$name];
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

    public function updateValue(string $name, $value) {
        if(isset($this->forms[$name])) {
            $form =& $this->forms[$name];

            if($form['type'] == 'checkbox') {
                if($form['selected'] && $value === '') {
                    unset($form['attributes']['checked']);
                }
                elseif (!$form['selected'] && $value !== '') {
                    $form['attributes']['checked'] = null;
                }
            }
            if($form['type'] == 'select' || $form['type'] == 'radio') {
                $form['selected'] = $value;
            } else {
                $form['value'] = $value;
                if ($form['type'] != 'file') {
                    $form['attributes']['value'] = $value;
                }
            }
        }

        return;
    }

    public function generateInput(array $input)
    {
        if($input['type'] == 'radio') {
            $html = [];

            foreach($input['value'] as $index => $attrValue) {
                $attr = ['type' => $input['type'], 'name' => $input['name'], 'value' => $attrValue];

                if(isset($input['selected']) && $input['selected'] === $attrValue) {
                    $attr['checked'] = null;
                }

                $html[] = ['label' => $index, 'input' => $this->generateTag('input', $attr)];
            }
        }
        else if($input['type'] == 'select') {
            $content = '';

            foreach($input['value'] as $index => $attrValue) {
                $attr = ['value' => $attrValue];

                if(isset($input['selected']) && $input['selected'] === $attrValue) {
                    $attr['selected'] = null;
                }

                $content .= $this->generateTag('option', $attr, $index);
            }

            $html = $this->generateTag('select', $input['attributes'], $content);
        }
        else if($input['type'] == 'textarea') {
            $html = $this->generateTag('textarea', $input['attributes'], $input['value']);
        }
        else {
            $html = $this->generateTag('input', $input['attributes']);
        }

        return $html;
    }

    public function generateInputs()
    {
        foreach ($this->items as $form) {
            $form->generateInput();
        }
    }

    public function addItem(object $object, ?string $key = null)
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
        $this->row('time', $name);

        $this->lastRow['attributes']['pattern'] = "[0-9]{2}:[0-9]{2}";

        return $this;
    }

    public function select(string $name)
    {
        $this->row('select', $name);
        $this->lastRow['selected'] = '';

        return $this;
    }

    public function radio(string $name)
    {
        $this->row('radio', $name);

        return $this;
    }

    public function checkbox(string $name)
    {
        $this->row('checkbox', $name);

        $this->lastRow['selected'] = false;

        return $this;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        $this->addItem(new FileInput($name, $destination, $ext), $name);

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

    public function accept(array $types = [])
    {
        $this->lastRow->accept($types);

        return $this;
    }

    public function class(?string $classes = null)
    {
        $this->lastRow->class($classes);

        return $this;
    }

    public function id(?string $id = null)
    {
        if($id === null) $id = $this->lastRow['name'];

        if($this->lastRow['type'] == 'radio') {
            $this->lastRow['id'] = $id;
        } else {
            $this->lastRow['attributes']['id'] = $id;
        }

        return $this;
    }

    public function selected($selected = true)
    {
        if($this->lastRow['type'] === 'checkbox') {
            $this->lastRow['attributes']['checked'] = null;
        }

        $this->lastRow['selected'] = $selected;

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
        if($placeholder !== null) {
            $this->lastRow['attributes']['placeholder'] = $placeholder;
        } else {
            unset($this->lastRow['attributes']['placeholder']);
        }

        return $this;
    }

    public function spellcheck(?bool $placeholder = null)
    {
        if($placeholder !== null) {
            $this->lastRow['attributes']['spellcheck'] = ($placeholder) ? 'true': 'false';
        } else {
            unset($this->lastRow['attributes']['spellcheck']);
        }

        return $this;
    }

    public function autocomplete(?string $value = null)
    {
        if($value !== null) {
            $this->lastRow['attributes']['autocomplete'] = $value;
        } else {
            unset($this->lastRow['attributes']['autocomplete']);
        }

        return $this;
    }

    public function tabindex(?int $index = null)
    {
        if($index !== null) {
            $this->lastRow['attributes']['tabindex'] = $index;
        } else {
            unset($this->lastRow['attributes']['tabindex']);
        }

        return $this;
    }

    public function label($label = false)
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

    public function RemoveAttribute($attribute)
    {
        unset($this->lastRow['attributes'][$attribute]);

        return $this;
    }

    public function validation()
    {
        $values = [];

        foreach($this->items as $key => $input) {
            $value = null;

            // TODO: Inject post data into objects
            if($this->keyExists($key)) {
                $value = $this->post[$key];
            }

            $result = $input->validation($value, $this->update);

            if($result !== null) {
                $values[$key] = $result;
            }
        }

        return $values;
    }

    public function verification() : array
    {
        $values = [];

        foreach($this->forms as $input) {
            $value = '';

            if($input['type'] === 'checkbox') {
                if(isset($this->post[$input['name']])) {
                    $value = $input['value'];
                } else {
                    $value = false;
                }
            }
            else if(isset($this->post[$input['name']])) {
                $value = $this->post[$input['name']];
            }

            if(array_key_exists('required', $input['attributes']) && $value === '') {
                throw new \Exception("{$input['name']} is required");
            }
            else if(!empty($value)) {
                if($input['type'] == 'time') {
                    $format = str_replace('/', '\/', $input['attributes']['pattern']);
                    if(preg_match("/$format/", $value) == 0) {
                        throw new \Exception("{$input['name']} is not a valid time");
                    }
                }
                else if($input['type'] == 'select' || $input['type'] == 'radio') {
                    $exist = false;

                    // Use the correct value type
                    if($key = array_search($value, $input['value'])) {
                        $value = $input['value'][$key];
                        $exist = true;
                    }

                    if(!$exist) {
                        throw new \Error("$value doesn't exist");
                    }
                }
            }

            $updated = false;

            if($input['type'] === 'file') {
                if($value !== '') {
                    $updated = true;
                }
            }
            else if($input['type'] == 'select' || $input['type'] == 'radio') {
                if($value != $input['selected']) {
                    $updated = true;
                }
            }
            else if($input['type'] === 'checkbox') {
                if(($input['selected'] && $value === '') || (!$input['selected'] && $value !== '')) {
                    $updated = true;
                }
            }
            else if($value !== $input['value']) {
                $updated = true;
            }

            if($updated) {
                $this->updateValue($input['name'], $value);
            }

            if((array_key_exists('required', $input['attributes']) && !$this->update) || $updated) {
                $values[$input['name']] = $value;
            }
        }

        return $values;
    }

    public function getForms() : array
    {
        $this->generateInputs();

        return $this->items;
    }
}