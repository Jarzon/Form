<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\ValidationException;

class FileInput extends Input
{
    protected string $destination;
    protected string $ext;
    protected array $accept;

    public function __construct(string $name, $form, string $destination, string $ext)
    {
        parent::__construct($name, $form);

        $this->setAttribute('type', 'file');

        $this->destination = $destination;
        $this->ext = $ext;
    }

    public function accept(array $types): Input
    {
        $this->accept = $types;
        $this->setAttribute('accept', implode(', ', $types));

        return $this;
    }

    public function multiple(bool $multiple): Input
    {
        if($multiple) {
            $this->setAttribute('multiple');
        } else {
            $this->deleteAttribute('multiple');
        }

        return $this;
    }

    public function value(mixed $value = ''): Input
    {
        $this->value = $value;

        return $this;
    }

    public function passValidation(mixed $value = null): bool
    {
        if(!parent::passValidation($value)) {
            return false;
        }

        if(!isset($this->form->files[$this->name]) && isset($this->form->post[$this->name])) {
            throw new \Error('form seems to miss enctype attribute');
        }

        return true;
    }

    public function isUpdated($value) : bool
    {
        $updated = false;

        if($value !== null) {
            $updated = true;
        }

        return $updated;
    }

    public function processValues(): void
    {
        parent::processValues();

        if(!isset($this->form->files[$this->name]) || $this->form->files[$this->name]['error'] === UPLOAD_ERR_NO_FILE) {
            return;
        }

        $value = $this->form->files[$this->name];

        $infos = [];

        // TODO: verify file type

        if(array_key_exists('multiple', $this->attributes)) {
            foreach ($value['error'] AS $index => $error) {
                $this->fileErrors($error);

                list($location, $name) = $this->fileMove($value['tmp_name'][$index], $this->destination, $this->ext);

                $this->postValues[] = [
                    'name' => $name,
                    'original_name' => $value['name'][$index],
                    'type' => $value['type'][$index],
                    'location' => $location,
                    'size' => $value['size'][$index],
                ];

                return;
            }
        }

        if(is_array($value['error'])) {
            throw new \Error('bypassed multiple limitation');
        }

        $this->fileErrors($value['error']);

        list($location, $name) = $this->fileMove($value['tmp_name'], $this->destination, $this->ext);

        $this->postValue = [
            'name' => $name,
            'original_name' => $value['name'],
            'type' => $value['type'],
            'location' => $location,
            'size' => $value['size']
        ];
    }

    private function fileErrors(int $error): void
    {
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new \Error('no file sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new ValidationException('exceeded filesize limit');
            default:
                throw new \Error('unknown upload error');
        }
    }

    private function fileMove(string $tmp_name, string $dest, string $ext = ''): array
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

    public function move_uploaded_file(string $tmp_name, string $dest): bool
    {
        return move_uploaded_file(
            $tmp_name,
            $dest
        );
    }
}
