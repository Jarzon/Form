<?php
namespace Jarzon\Input;

use Jarzon\TextBasedInput;

class FileInput extends TextBasedInput
{
    protected $destination;
    protected $ext;
    protected $accept;
    protected $post = [];
    protected $files = [];

    public function __construct(string $name, string $destination, string $ext, array $post, array $files)
    {
        parent::__construct($name);

        $this->setAttribute('type', 'file');

        $this->destination = $destination;
        $this->ext = $ext;

        $this->post = $post;
        $this->files = $files;
    }

    public function accept($types) {
        $this->accept = $types;
        $this->setAttribute('accept', implode(', ', $types));

        return $this;
    }

    public function multiple(bool $multiple)
    {
        if($multiple) {
            $this->setAttribute('multiple');
        } else {
            $this->deleteAttribute('multiple');
        }

        return $this;
    }

    public function value($value = '') {
        $this->value = $value;

        return $this;
    }

    public function passValidation($value = null): bool
    {
        if(!isset($this->files[$this->name]) && isset($this->post[$this->name])) {
            throw new \Error('form seems to miss enctype attribute');
        }

        return true;
    }

    public function isUpdated($value) : bool
    {
        $updated = false;

        if($value !== '') {
            $updated = true;
        }

        return $updated;
    }

    public function validation($value = null, $update = false)
    {
        parent::validation($value, $update);

        if(isset($this->files[$this->name]) && $this->files[$this->name]['error'] !== UPLOAD_ERR_NO_FILE) {
            $value = $this->files[$this->name];
        } else {
            return;
        }

        $infos = [];

        // TODO: verify file type

        if(array_key_exists('multiple', $this->attributes)) {
            foreach ($value['error'] AS $index => $error) {
                $this->fileErrors($error);

                list($location, $name) = $this->fileMove($value['tmp_name'][$index], $this->destination, $this->ext);

                $infos[] = [
                    'name' => $name,
                    'original_name' => $value['name'][$index],
                    'type' => $value['type'][$index],
                    'location' => $location,
                    'size' => $value['size'][$index],
                ];
            }
        } else {
            if(is_array($value['error'])) {
                throw new \Error('bypassed multiple limitation');
            }

            $this->fileErrors($value['error']);

            list($location, $name) = $this->fileMove($value['tmp_name'], $this->destination, $this->ext);

            $infos = [
                'name' => $name,
                'original_name' => $value['name'],
                'type' => $value['type'],
                'location' => $location,
                'size' => $value['size']
            ];
        }

        $value = $infos;

        return $value;
    }

    private function fileErrors($error)
    {
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Exception('no file sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \Exception('exceeded filesize limit');
            default:
                throw new \Error('unknown upload error');
        }
    }

    private function fileMove(string $tmp_name, string $dest, string $ext = '')
    {
        $name = sha1_file($tmp_name);

        $file = sprintf('%s/%s',
            $dest,
            $name
        );

        if($ext != '') {
            $file .= ".$ext";
        }

        if (!$this->move_uploaded_file(
            $tmp_name,
            $file
        )) {
            throw new \Error('failed to move uploaded file');
        }

        return [$file, $name];
    }

    public function move_uploaded_file($tmp_name, $dest)
    {
        return move_uploaded_file(
            $tmp_name,
            $dest
        );
    }
}