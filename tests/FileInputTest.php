<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class FileInputTest extends TestCase
{
    /**
     * @var  vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('root', null, [
            'temp' => [
                'test.txt' => '',
            ],
            'data' => [
            ],
        ]);
    }

    /**
     * @expectedException     \Error
     * @expectedExceptionMessage form seems to miss enctype attribute
     */
    public function testFileFormMissingEnctype()
    {
        $form = new Form(['test' => 's'], []);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg']);

        $form->validation();
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is required
     */
    public function testFileEmptyRequired()
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
            'size' => 4,
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
            'size' => 4
        ], $values['test']);
    }

    public function testGetFormsFile()
    {
        $form = new Form([], []);

        $form
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg'])
            ->multiple();

        $this->assertEquals(
            '<input name="test" type="file" accept=".jpg, .jpeg" multiple>',
            $form->getInput('test')->html
        );
    }
}