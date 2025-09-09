<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class FileInputTest extends TestCase
{
    private vfsStreamDirectory $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('root', null, [
            'temp' => [
                'test.txt' => '',
                'test2.txt' => '',
            ],
            'data' => [
            ],
        ]);
    }

    public function testFileFormMissingEnctype()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('form seems to miss enctype attribute');

        $form = new Form(['test' => 's'], []);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg']);

        $form->validation();
    }

    public function testFileEmptyRequired()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is required');

        $form = new Form([], ['test' => [
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'full_path' => '',
            'size' => 4,
            'error' => UPLOAD_ERR_NO_FILE,
        ]]);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg'])
            ->required();

        $form->validation();
    }

    public function testFileEmpty()
    {
        $form = new Form([], ['test' => [
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'size' => 4,
            'error' => UPLOAD_ERR_NO_FILE,
        ]]);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg']);

        $values = $form->validation();

        $this->assertEquals([], $values);
    }

    public function testFileUpload()
    {
        file_put_contents(__DIR__.'/files/da39a3ee5e6b4b0d3255bfef95601890afd80709', '');

        $form = new Form([], ['test' => [
            'name' => 'test',
            'type' => 'jpg',
            'tmp_name' => __DIR__.'/files/da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'size' => 4,
            'error' => UPLOAD_ERR_OK,
        ]]);

        $form
            ->file('test', __DIR__.'/files/dest')
            ->accept(['.jpg', '.jpeg']);

        $values = $form->validation();

        $this->assertEquals([
            'test' => [
                'name' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
                'original_name' => 'test',
                'type' => 'jpg',
                'location' => __DIR__ . '/files/dest/da39a3ee5e6b4b0d3255bfef95601890afd80709',
                'size' => 4
            ]
        ], $values);

        unlink(__DIR__.'/files/dest/da39a3ee5e6b4b0d3255bfef95601890afd80709');
    }

    public function testFileValue()
    {
        $form = new Form(['test' => 'test.txt'], ['test' => [
            'name' => 'test.txt',
            'type' => 'text',
            'tmp_name' => vfsStream::url('root/temp/test.txt'),
            'size' => filesize(vfsStream::url('root/temp/test.txt')),
            'error' => UPLOAD_ERR_OK,
        ]]);

        $form
            ->file('test', vfsStream::url('root/data'))
            ->accept(['.txt', '.text']);

        $values = $form->validation();

        $this->assertTrue(file_exists('vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709'));

        $this->assertEquals([
            'name' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'original_name' => 'test.txt',
            'type' => 'text',
            'location' => 'vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'size' => 0
        ], $values['test']);
    }

    public function testFileNumberOfFiles()
    {
        $this->expectException(ValidationException::class);

        $form = new Form([], ['test' => [
            'name' => ['test.txt', 'test.txt'],
            'type' => ['text', 'text'],
            'tmp_name' => [vfsStream::url('root/temp/test.txt'), vfsStream::url('root/temp/test2.txt')],
            'size' => [filesize(vfsStream::url('root/temp/test.txt')), filesize(vfsStream::url('root/temp/test2.txt'))],
            'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
        ]]);

        $form
            ->file('test', vfsStream::url('root/data'))
            ->accept(['.txt', '.text'])
            ->multiple(1);

        $values = $form->validation();

        $this->assertTrue(file_exists('vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709'));

        $this->assertEquals([
            'name' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'original_name' => 'test.txt',
            'type' => 'text',
            'location' => 'vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'size' => 0
        ], $values['test']);
    }

    public function testNoFileWithMutliple()
    {
//        $this->expectException(ValidationException::class);

        $form = new Form(['text' => 'testing'], []);

        $form
            ->text('text');
       $form->file('test[]', vfsStream::url('root/data'))
            ->accept(['.txt', '.text'])
            ->multiple(5);

        $values = $form->validation();

        $this->assertEquals(['text' => 'testing'], $values);
    }

    public function testGetFormsFile()
    {
        $form = new Form([], []);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg'])
            ->multiple();

        $this->assertEquals(
            '<input name="test" type="file" accept=".jpg, .jpeg" multiple data-maxNumberOfFiles="20">',
            $form->getInput('test')->html
        );
    }
}