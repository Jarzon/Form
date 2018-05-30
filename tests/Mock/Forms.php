<?php
namespace Tests\Mock;

Class Forms extends \Jarzon\Forms
{
    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        $this->addItem(new FileInput($name, $destination, $ext), $name);

        return $this;
    }
}