<?php declare(strict_types=1);
namespace Jarzon;

use Jarzon\Input\{
    CheckboxInput,
    ColorInput,
    CsrfInput,
    CurrencyInput,
    DataListInput,
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
    UrlInput};

class Form
{
    protected array $inputs = [];
    protected string $submitName = '';
    public array $post = [];
    public array $files = [];
    public bool $update = false;
    public bool $repeat = false;
    public array $postValues = [];
    public string $postPrefix = '';

    /** @var ListBasedInput|TextBasedInput|DigitBasedInput|FileInput|FormTag|SubmitInput */
    protected $lastRow;

    public function __construct(array $post, array $files = [], string $postPrefix = '')
    {
        $this->post = $post;
        $this->files = $files;
        if($postPrefix !== '') {
            $this->postPrefix = $postPrefix;
        }

        $form = new FormTag();

        $this->lastRow = $form;

        $this->addInput($form, 'form');
    }

    /**
     * @return Input|FormTag
     */
    public function __invoke(string $name)
    {
        if($this->keyExists($name)) {
            return $this->getInput($name);
        }

        throw new \Exception("$name input doesn't exist");
    }

    public function submitted(string|null $name = null): bool
    {
        return array_key_exists($name ?? $this->submitName, $this->post);
    }

    public function updateValues(object|array $values = []): void
    {
        if(empty($values)) {
            $values = $this->post;
        } else {
            $this->update = true;
        }

        foreach ($values as $name => $value) {
            $this->updateValue($name, $value);
        }
    }

    public function updateValue(string $name, mixed $value): void
    {
        if($this->keyExists($name)) {
            $input = $this->getInput($name);
            if($input instanceof CheckboxInput) {
                $input->selected($value);
            } else {
                $input->value($value);
            }
        }
    }

    public function getForms(): array
    {
        return $this->inputs;
    }

    public function getNumberOfRows(): int
    {
        $max = 0;
        foreach($this->inputs as $input) {
            if(!$input instanceof Input || !isset($this->post[$input->name]) || !is_array($this->post[$input->name])) continue;

            $max = max($max, count($this->post[$input->name]));
        }
        return $max - 1;
    }

    public function validation(bool $groupColumns = false): array
    {
        foreach($this->inputs as $key => $input) {
            // Dont validate submit and form tags
            if(!is_subclass_of($input, Input::class) || !$this->keyExists($key)) {
                continue;
            }

            $input->processValues();

            $result = $input->validation();

            if($this->repeat && $result !== null) {
                foreach ($result as $i => $v) {
                    if($groupColumns && !isset($this->postValues[$key])) {
                        $this->postValues[$key] = [];
                    } else if(!$groupColumns && !isset($this->postValues[$i])) {
                        $this->postValues[$i] = [];
                    }

                    if($groupColumns) {
                        $this->postValues[$key][$i] = $v;
                    } else {
                        $this->postValues[$i][$key] = $v;
                    }
                }

                continue;
            }

            if($result !== null) {
                $this->postValues[$key] = $result;
            }
        }

        return $this->postValues;
    }

    /*
     * Collection methods
     */

    protected function addInput(object $object, string|null $key = null, bool $lastRow = true): void
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

    public function getInput($key)
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
    public function submit(string $name = 'save'): Form
    {
        if($this->keyExists($name)) {
            throw new \Error("Trying to redeclare a existing submit input named: $name");
        }

        $this->addInput(new SubmitInput($name), $name);

        if($this->keyExists('form') && !$this->keyExists('/form')) {
            $this->addInput(new FormTag(true), '/form', false);
        }

        $this->submitName = $name;

        return $this;
    }

    public function hidden(string $name): Form
    {
        $this->addInput(new HiddenInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function text(string $name): Form
    {
        $this->addInput(new TextInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function textarea(string $name): Form
    {
        $this->addInput(new TextareaInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function password(string $name): Form
    {
        $this->addInput(new PasswordInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function email(string $name): Form
    {
        $this->addInput(new EmailInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function url(string $name): Form
    {
        $this->addInput(new UrlInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function search(string $name): Form
    {
        $this->addInput(new SearchInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function tel(string $name): Form
    {
        $this->addInput(new TelInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function color(string $name): Form
    {
        $this->addInput(new ColorInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function number(string $name): Form
    {
        $this->addInput(new NumberInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function float(string $name): Form
    {
        $this->addInput(new FloatInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function currency(string $name): Form
    {
        $this->addInput(new CurrencyInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function range(string $name): Form
    {
        $this->addInput(new RangeInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function date(string $name): Form
    {
        $this->addInput(new DateInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function time(string $name): Form
    {
        $this->addInput(new TimeInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function select(string $name): Form
    {
        $this->addInput(new SelectInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function radio(string $name): Form
    {
        $this->addInput(new RadioInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function checkbox(string $name): Form
    {
        $this->addInput(new CheckboxInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = ''): Form
    {
        $this->addInput(new FileInput($this->postPrefix.$name, $this, $destination, $ext), $name);

        $this->getInput('form')->setAttribute('enctype', 'multipart/form-data');

        return $this;
    }

    public function datalist(string $name): Form
    {
        $this->addInput(new DataListInput($this->postPrefix.$name, $this), $name);

        return $this;
    }

    public function csrf(string $name = '_csrfToken'): Form
    {
        $this->addInput(new CsrfInput($this->postPrefix.$name, $this), $name);

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

    public function mandatory(bool $mandatory = true): Form
    {
        $this->lastRow->mandatory($mandatory);

        return $this;
    }

    public function value($value = ''): Form
    {
        $this->lastRow->value($value);

        return $this;
    }

    public function setNegativeValue($value = 0): Form
    {
        if(!$this->lastRow instanceof CheckboxInput) {
            throw new \Exception("Illegal use of setNegativeValue() on unsupported tag");
        }

        $this->lastRow->setNegativeValue($value);

        return $this;
    }

    public function class(string|null $classes = null): Form
    {
        $this->lastRow->class($classes);

        return $this;
    }

    public function id(string|null $id = null): Form
    {
        $this->lastRow->id($id);

        return $this;
    }

    public function min(...$min): Form
    {
        if($this->lastRow instanceof FormTag) {
            throw new \Exception("Illegal use of min() on unsupported tag");
        }

        $this->lastRow->min(...$min);

        return $this;
    }

    public function max(...$max): Form
    {
        if($this->lastRow instanceof FormTag) {
            throw new \Exception("Illegal use of max() on unsupported tag");
        }

        $this->lastRow->max(...$max);

        return $this;
    }

    public function decimal(int $decimals): Form
    {
        if(!$this->lastRow instanceof CurrencyInput) {
            throw new \Exception("Illegal use of decimal() on unsupported tag");
        }

        $this->lastRow->decimal($decimals);

        return $this;
    }

    public function accept(array $types = []): Form
    {
        if(!$this->lastRow instanceof FileInput) {
            throw new \Exception("Illegal use of accept() on unsupported tag");
        }

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
        if(!$this->lastRow instanceof FileInput && !$this->lastRow instanceof EmailInput) {
            throw new \Exception("Illegal use of multiple() on unsupported tag");
        }

        $this->lastRow->multiple($multiple);

        return $this;
    }

    public function pattern(string|null $pattern = null, string|null $message = null): Form
    {
        $this->lastRow->pattern($pattern, $message);

        return $this;
    }

    public function placeholder(string|null $placeholder = null): Form
    {
        $this->lastRow->placeholder($placeholder);

        return $this;
    }

    public function spellcheck(bool|null $placeholder = null): Form
    {
        $this->lastRow->spellcheck($placeholder);

        return $this;
    }

    public function autocomplete(string|null $value = null): Form
    {
        $this->lastRow->autocomplete($value);

        return $this;
    }

    public function tabindex(int|null $index = null): Form
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

    public function addOption(string $text, $value, array $attributes = []): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of addOption() on unsupported tag");
        }

        $this->lastRow->addOption($text, $value, $attributes);

        return $this;
    }

    public function addOptions(array $options): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of addOptions() on unsupported tag");
        }

        $this->lastRow->addOptions($options);

        return $this;
    }

    public function group(string $name): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of group() on unsupported tag");
        }

        $this->lastRow->group($name);

        return $this;
    }

    public function setGroupAttribute(string $name, string $value): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of setGroupAttribute() on unsupported tag");
        }

        $this->lastRow->setGroupAttribute($name, $value);

        return $this;
    }

    public function groupBind(string $name = '', string $class = ''): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of groupBind() on unsupported tag");
        }

        $this->lastRow->groupBind($name, $class);

        return $this;
    }

    public function groupAction(string $actionCallback = '', string $actionContent = ''): Form
    {
        if(!$this->lastRow instanceof SelectInput) {
            throw new \Exception("Illegal use of groupBind() on unsupported tag");
        }

        $this->lastRow->groupAction($actionCallback, $actionContent);

        return $this;
    }

    public function bindOptionText(string $value): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of bindOptionText() on unsupported tag");
        }

        $this->lastRow->bindOptionText($value);

        return $this;
    }

    public function bindOptionValue(string $value): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of bindOptionValue() on unsupported tag");
        }

        $this->lastRow->bindOptionValue($value);

        return $this;
    }

    public function bindOptionAttribute(string $name, string $value): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of bindOptionAttribute() on unsupported tag");
        }

        $this->lastRow->bindOptionAttribute($name, $value);

        return $this;
    }

    public function bindValues(array $values): Form
    {
        if(!$this->lastRow instanceof SelectInput && !$this->lastRow instanceof DataListInput && !$this->lastRow instanceof RadioInput) {
            throw new \Exception("Illegal use of bindValues() on unsupported tag");
        }

        $this->lastRow->bindValues($values);

        return $this;
    }
}
