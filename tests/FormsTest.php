<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class FormsTest extends TestCase
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
     * @expectedException     \Exception
     * @expectedExceptionMessage test is too short
     */
    public function testLengthLowerThatMin()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->text('test')
            ->min(4);

        $forms->verification();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is required
     */
    public function testLengthNull()
    {
        $forms = new Forms(['test' => '']);

        $forms
            ->text('test')
            ->min(4)
            ->max(10)
            ->required();

        $forms->verification();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is too long
     */
    public function testLengthHigherThatMax()
    {
        $forms = new Forms(['test' => '123456789ab']);

        $forms
            ->text('test')
            ->max(10);

        $forms->verification();
    }


    public function testValidDate()
    {
        $forms = new Forms(['test' => '12/04/2014']);

        $forms
            ->date('test');

        $values = $forms->verification();

        $this->assertEquals(['test' => '12/04/2014'], $values);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid date
     */
    public function testInvalidDate()
    {
        $forms = new Forms(['test' => '00/00/0000']);

        $forms
            ->date('test');

        $forms->verification();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid email
     */
    public function testInvalidEmail()
    {
        $forms = new Forms(['test' => 'asdf']);

        $forms
            ->email('test');

        $forms->verification();
    }

    /**
     * @expectedException     \Error
     * @expectedExceptionMessage 123456789ab doesn't exist
     */
    public function testRadioValueException()
    {
        $forms = new Forms(['test' => '123456789ab']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $forms->verification();
    }

    /**
     * @expectedException     \Error
     * @expectedExceptionMessage form seems to miss enctype attribute
     */
    public function testFileFormMissingEnctype()
    {
        $_FILES = [];

        $forms = new Forms(['test' => '']);

        $forms
            ->file('test', '/')
            ->types(['.jpg', '.jpeg']);

        $values = $forms->verification();

        $this->assertEquals('', $values['test']);
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
            ->types(['.jpg', '.jpeg'])
            ->required();

        $forms->verification();
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
            ->types(['.jpg', '.jpeg']);

        $values = $forms->verification();

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
            ->types(['.jpg', '.jpeg']);

        $values = $forms->verification();

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

    public function testCheckboxChecked()
    {
        $forms = new Forms(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->verification();

        $this->assertEquals('testy', $params['test']);
    }

    public function testCheckboxUnchecked()
    {
        $forms = new Forms([]);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->verification();

        $this->assertEquals(false, $params['test']);
    }

    public function testCheckboxCheckedBool()
    {
        $forms = new Forms(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->verification();

        $this->assertEquals(true, $params['test']);
    }

    public function testCheckboxUncheckedBool()
    {
        $forms = new Forms([]);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->verification();

        $this->assertEquals(false, $params['test']);
    }

    public function testRadioValue()
    {
        $forms = new Forms(['test' => 'testy']);

        $forms
            ->radio('test')
            ->value(['test' => 'test', 'testy' => 'testy'])
            ->selected('test');

        $values = $forms->verification();

        $this->assertEquals('testy', $values['test']);
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
            ->types(['.txt', '.text']);

        $values = $forms->verification();

        $this->assertTrue(file_exists('vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709'));

        $this->assertEquals([
            'name' => 'da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'original_name' => 'test.txt',
            'type' => 'text',
            'location' => 'vfs://root/data/da39a3ee5e6b4b0d3255bfef95601890afd80709',
            'size' => 4
        ], $values['test']);
    }

    public function testGetFormsText()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->text('test')
            ->min(4)
            ->max(10)
            ->class('testClass secondClass')
            ->attributes(['custom-attr' => 'customValue']);

        $forms
            ->text('test2');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" minlength="4" maxlength="10" class="testClass secondClass" custom-attr="customValue">', $content['test']['html']);
        $this->assertEquals('<input name="test2" type="text">', $content['test2']['html']);

        $this->assertEquals('test', $content['test']['label']);

        $html = '';

        foreach ($content as $form) {
            if($form['type'] == 'radio') {
                foreach ($form['html'] as $radio) {
                    $html .= "<label>{$radio['input']}<br>{$radio['label']}</label>";
                }
            } else {
                $html .= "<label>{$form['label']}<br>{$form['html']}</label>";
            }
        }

        $this->assertEquals('<label>test<br><input name="test" type="text" minlength="4" maxlength="10" class="testClass secondClass" custom-attr="customValue"></label><label>test2<br><input name="test2" type="text"></label>', $html);
    }

    public function testFormLabel()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->text('test')
            ->min(4)
            ->max(10)
            ->label(false);

        $content = $forms->getForms();

        $this->assertEquals(false, isset($content['test']['label']));
    }

    public function testGetFormsTextarea()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->textarea('test')
            ->min(0)
            ->max(500);

        $content = $forms->getForms();

        $this->assertEquals('<textarea name="test" minlength="0" maxlength="500"></textarea>', $content['test']['html']);
    }

    public function testGetFormsNumber()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->number('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="number" step="1" min="4" max="10">', $content['test']['html']);
    }

    public function testGetFormsFloat()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->float('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="number" step="0.01" min="4" max="10">', $content['test']['html']);
    }

    public function testGetFormsDate() {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->date('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" pattern="(0?[1-9]|[12][0-9]|3[01])[- /.](0?[1-9]|1[012])[- /.](19|20)\d\d">', $content['test']['html']);
    }

    public function testGetFormsEmail()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->email('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="email" minlength="4" maxlength="10">', $content['test']['html']);
    }

    public function testGetFormsSelect()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->select('test')
            ->value(['test' => 'test'])
            ->selected('test');

        $content = $forms->getForms();

        $this->assertEquals('<select name="test"><option value="test" selected>test</option></select>', $content['test']['html']);
    }

    public function testGetFormsRadio()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->radio('test')
            ->value(['test' => 'test']);

        $content = $forms->getForms();

        $this->assertEquals('<input type="radio" name="test" value="test">', $content['test']['html'][0]['input']);

        $forms->selected('test');

        $content = $forms->getForms();

        $this->assertEquals('<input type="radio" name="test" value="test" checked>', $content['test']['html'][0]['input']);
    }

    public function testGetFormsCheckbox()
    {
        $forms = new Forms(['test' => 'a']);

        $forms
            ->checkbox('test')
            ->value('test')
            ->selected();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="checkbox" value="test" checked>', $content['test']['html']);
    }

    public function testUpdateValue()
    {
        $forms = new Forms(['test' => 'good']);

        $forms
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="wrong" minlength="4" maxlength="10">', $content['test']['html']);

        $forms->verification();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']['html']);
    }

    public function testUpdateUnexistingValue()
    {
        $forms = new Forms(['test' => 'good']);

        $forms
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $forms->updateValues(['test' => 'good', 'asdf' => 'asdf']);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']['html']);
    }

    public function testUpdateValuesSelect()
    {
        $forms = new Forms(['fruits' => 'oranges']);

        $forms
            ->select('fruits')
            ->value(['apples' => 'apples', 'oranges' => 'oranges']);

        $content = $forms->getForms();

        $this->assertEquals('<select name="fruits"><option value="apples">apples</option><option value="oranges">oranges</option></select>', $content['fruits']['html']);

        $forms->verification();

        $content = $forms->getForms();

        $this->assertEquals('<select name="fruits"><option value="apples">apples</option><option value="oranges" selected>oranges</option></select>', $content['fruits']['html']);

    }

    public function testUpdateValuesCheckbox()
    {
        $forms = new Forms(['fruits' => 'apples']);

        $forms
            ->checkbox('fruits')
            ->value('apples');

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples">', $content['fruits']['html']);

        $forms->verification();

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples" checked>', $content['fruits']['html']);
    }

    public function testGetFormsFile()
    {
        $forms = new Forms([]);

        $forms
            ->file('test', '/')
            ->types(['.jpg', '.jpeg'])
            ->multiple();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="file" accept=".jpg, .jpeg" multiple>', $content['test']['html']);
    }
}