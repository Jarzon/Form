<?php
namespace Tests\Mock;

Class FileInput extends \Jarzon\Input\FileInput
{
    public function move_uploaded_file($tmp_name, $dest) {
        return rename(
            $tmp_name,
            $dest
        );
    }
}