<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

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
        $_FILES = [];
        $_POST = ['test' => ''];

        $forms = new Forms($_POST);

        $forms
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg']);

        $values = $forms->validation();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is required
     */
    public function testFileEmptyRequired()
    {
        $_FILES['test'] = [
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'size' => 4,
            'error' => UPLOAD_ERR_NO_FILE,
        ];

        $forms = new Forms([]);

        $forms
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg'])
            ->required();

        $forms->validation();
    }

    public function testFileEmpty()
    {
        $_FILES['test'] = [
            'name' => '',
            'type' => '',
            'tmp_name' => '',
            'size' => 4,
            'error' => UPLOAD_ERR_NO_FILE,
        ];

        $forms = new Forms([]);

        $forms
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg']);

        $values = $forms->validation();

        $this->assertEquals([], $values);
    }

    public function testFileUpload()
    {
        file_put_contents(__DIR__.'/files/da39a3ee5e6b4b0d3255bfef95601890afd80709', '');

        $_FILES['test'] = [
            'name' => 'test',
            'type' => 'jpg',
            'tmp_name' => __DIR__.'/files/da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'size' => 4,
            'error' => UPLOAD_ERR_OK,
        ];

        $forms = new Forms([]);

        $forms
            ->file('test', __DIR__.'/files/dest')
            ->accept(['.jpg', '.jpeg']);

        $values = $forms->validation();

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
        $_FILES['test'] = [
            'name' => 'test.txt',
            'type' => 'text',
            'tmp_name' => vfsStream::url('root/temp/test.txt'),
            'size' => 4,
            'error' => UPLOAD_ERR_OK,
        ];

        $forms = new Forms(['test' => 'test.txt']);

        $forms
            ->file('test', vfsStream::url('root/data'))
            ->accept(['.txt', '.text']);

        $values = $forms->validation();

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
        $forms = new Forms([]);

        $forms
            ->file('test', '/')
            ->accept(['.jpg', '.jpeg'])
            ->multiple();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="file" accept=".jpg, .jpeg" multiple>', $content['test']->html);
    }
}