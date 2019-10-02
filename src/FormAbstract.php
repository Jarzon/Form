<?php
namespace Jarzon;

abstract class FormAbstract
{
    protected $form;

    public function __construct(array $form)
    {
        $this->form = new Form($_POST, $_FILES);
    }

    public function __invoke(string $name)
    {
        return $this->form($name);
    }

    public function updateValues($settings): void
    {
        $this->form->updateValues($settings);
    }

    public function submitted(): bool
    {
        return $this->form->submitted();
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