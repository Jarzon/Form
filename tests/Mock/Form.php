<?php
namespace Tests\Mock;

Class Form extends \Jarzon\Form
{
    public function file(string $name, string $destination = '/tmp/', string $ext = ''): \Jarzon\Input\FileInput
    {
        $input = new FileInput($name, $this, $destination, $ext);

        $this->addInput($input, $name);

        return $input;
    }
}