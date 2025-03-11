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

            $result = $input->inputValidation();

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

    public function attributes($attributes = []): static
    {
        foreach ($attributes as $name => $value) {
            $this->lastRow->setAttribute($name, $value);
        }

        return $this;
    }

    /*
     * Input types
     */
    public function submit(string $name = 'save'): SubmitInput
    {
        if($this->keyExists($name)) {
            throw new \Error("Trying to redeclare a existing submit input named: $name");
        }

        $input = new SubmitInput($name, $this);
        $this->addInput($input, $name);

        if($this->keyExists('form') && !$this->keyExists('/form')) {
            $this->addInput(new FormTag(true), '/form', false);
        }

        $this->submitName = $name;

        return $input;
    }

    public function hidden(string $name): HiddenInput
    {
        $input = new HiddenInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function text(string $name): TextInput
    {
        $input = new TextInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function textarea(string $name): TextareaInput
    {
        $input = new TextareaInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function password(string $name): PasswordInput
    {
        $input = new PasswordInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function email(string $name): EmailInput
    {
        $input = new EmailInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function url(string $name): UrlInput
    {
        $input = new UrlInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function search(string $name): SearchInput
    {
        $input = new SearchInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function tel(string $name): TelInput
    {
        $input = new TelInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function color(string $name): ColorInput
    {
        $input = new ColorInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function number(string $name): NumberInput
    {
        $input = new NumberInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function float(string $name): FloatInput
    {
        $input = new FloatInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function currency(string $name): CurrencyInput
    {
        $input = new CurrencyInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function range(string $name): RangeInput
    {
        $input = new RangeInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function date(string $name): DateInput
    {
        $input = new DateInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function time(string $name): TimeInput
    {
        $input = new TimeInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function select(string $name): SelectInput
    {
        $input = new SelectInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function radio(string $name): RadioInput
    {
        $input = new RadioInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function checkbox(string $name): CheckboxInput
    {
        $input = new CheckboxInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function file(string $name, string $destination = '/tmp/', string $ext = ''): FileInput
    {
        $input = new FileInput($this->postPrefix.$name, $this, $destination, $ext);

        $this->addInput($input, $name);

        $this->getInput('form')->setAttribute('enctype', 'multipart/form-data');

        return $input;
    }

    public function datalist(string $name): DataListInput
    {
        $input = new DataListInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
    }

    public function csrf(string $name = '_csrfToken'): CsrfInput
    {
        $input = new CsrfInput($this->postPrefix.$name, $this);
        $this->addInput($input, $name);

        return $input;
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

    public function repeat(): Form
    {
        $this->repeat = true;

        return $this;
    }
}
