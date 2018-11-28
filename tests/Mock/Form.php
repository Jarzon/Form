<?php
namespace Tests\Mock;

Class Form extends \Jarzon\Form
{
    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        $this->addInput(new FileInput($name, $destination, $ext, $this->post, $this->files), $name);

        return $this;
    }
}