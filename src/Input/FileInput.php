<?php declare(strict_types=1);
namespace Jarzon\Input;

use Jarzon\Input;
use Jarzon\ValidationException;

class FileInput extends Input
{
    protected string $destination;
    protected string $ext;
    protected array $accept;
    protected int $limit = 2_097_152;
    protected int $maxNumberOfFiles = 20;

    public function __construct(
        string $name,
               $form,
        string $destination,
        string $ext
    ) {
        parent::__construct($name, $form);

        $this->setAttribute('type', 'file');

        $this->destination = $destination;
        $this->ext = $ext;
    }

    public function accept(array $types): static
    {
        $this->accept = $types;
        $this->setAttribute('accept', implode(', ', $types));

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        $this->setAttribute('data-limit', $limit);

        return $this;
    }

    public function multiple(int $maxNumberOfFiles = 20, bool $remove = false): static
    {
        if($remove) {
            $this->maxNumberOfFiles = 1;
            $this->deleteAttribute('multiple');
            $this->deleteAttribute('data-maxNumberOfFiles');
        } else {
            $this->maxNumberOfFiles = $maxNumberOfFiles;
            $this->setAttribute('multiple');
            if($maxNumberOfFiles > 0) $this->setAttribute('data-maxNumberOfFiles', $maxNumberOfFiles);
        }

        return $this;
    }

    public function value(mixed $value = ''): static
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

        if($this->hasAttribute('multiple')) {
            if(isset($this->form->files[$this->name]) && count($this->form->files[$this->name]['name']) > $this->maxNumberOfFiles) {
                throw new ValidationException("{$this->name} have too many files", 71);
            }

            $currentSize = 0;
            foreach ($value['size'] AS $index => $val) {
                $currentSize += $val;
            }

            if($currentSize > $this->limit) {
                throw new ValidationException("{$this->name} file is too big", 70);
            }
        } else {
            if(isset($this->form->files[$this->name]) && $this->form->files[$this->name]['size'] > $this->limit) {
                throw new ValidationException("{$this->name} file is too big", 70);
            }
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

        $name = $this->hasAttribute('multiple')? str_replace('[]', '', $this->name) : $this->name;

        if(!isset($this->form->files[$name])) {
            return;
        }

        $value = $this->form->files[$name];

        // TODO: verify file type

        if($this->hasAttribute('multiple')) {
            foreach ($value['name'] AS $index => $val) {
                $this->fileErrors($value['error'][$index]);

                list($location, $filename) = $this->fileMove($value['tmp_name'][$index], $this->destination, $this->ext);

                $this->postValue[] = [
                    'name' => $filename,
                    'original_name' => $value['name'][$index],
                    'type' => $value['type'][$index],
                    'location' => $location,
                    'size' => $value['size'][$index],
                ];
            }

            return;
        }

        if($this->form->files[$name]['error'] === UPLOAD_ERR_NO_FILE) {
            return;
        }

        if(is_array($value['error'])) {
            throw new \Error('bypassed multiple limitation');
        }

        $this->fileErrors($value['error']);

        list($location, $filename) = $this->fileMove($value['tmp_name'], $this->destination, $this->ext);

        $this->postValue = [
            'name' => $filename,
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
                throw new ValidationException('exceeded filesize limit', 30);
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
