<?php declare(strict_types=1);
namespace Jarzon;

abstract class FormAbstract
{
    protected Form $form;

    public function __construct()
    {
        $this->form = new Form($_POST, $_FILES);
    }

    public function __invoke(string $name)
    {
        return ($this->form)($name);
    }

    public function updateValues(object|array $settings): void
    {
        $this->form->updateValues($settings);
    }

    public function submitted(string|null $name = null): bool
    {
        return $this->form->submitted($name);
    }

    public function validation(): array
    {
        return $this->form->validation();
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function getForms(): array
    {
        return $this->form->getForms();
    }
}
