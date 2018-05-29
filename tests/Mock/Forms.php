<?php
namespace Tests\Mock;

Class Forms extends \Jarzon\Forms
{
    public function file(string $name, string $destination = '/tmp/', string $ext = '')
    {
        return new FileInput($name, $destination, $ext);
    }
}