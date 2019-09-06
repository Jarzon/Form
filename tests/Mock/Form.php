<?php
namespace Tests\Mock;

Class Form extends \Jarzon\Form
{
    public function file(string $name, string $destination = '/tmp/', string $ext = ''): \Jarzon\Form
    {
        $this->addInput(new FileInput($name, $this, $destination, $ext), $name);

        return $this;
    }
}