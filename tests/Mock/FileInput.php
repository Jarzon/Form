<?php
namespace Tests\Mock;

Class FileInput extends \Jarzon\Input\FileInput
{
    public function move_uploaded_file(string $tmp_name, string $dest): bool
    {
        return rename(
            $tmp_name,
            $dest
        );
    }
}