<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Form;

class CheckboxInputTest extends TestCase
{
    public function testCheckboxChecked()
    {
        $forms = new Form(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->validation();

        $this->assertEquals('testy', $params['test']);
    }

    public function testCheckboxUnchecked()
    {
        $forms = new Form([]);

        $forms
            ->checkbox('test')
            ->value('testy');

        $params = $forms->validation();

        $this->assertEquals(false, $params['test']);
    }

    public function testCheckboxCheckedBool()
    {
        $forms = new Form(['test' => '1234']);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->validation();

        $this->assertEquals(true, $params['test']);
    }

    public function testCheckboxUncheckedBool()
    {
        $forms = new Form([]);

        $forms
            ->checkbox('test')
            ->value(true);

        $params = $forms->validation();

        $this->assertEquals(false, $params['test']);
    }

    public function testGetFormsCheckbox()
    {
        $forms = new Form([]);

        $forms
            ->checkbox('test')
            ->value('test')
            ->selected();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="checkbox" value="test" checked>', $content['test']->html);

        $forms->validation();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="checkbox" value="test">', $content['test']->html);
    }

    public function testUpdateValuesCheckbox()
    {
        $forms = new Form(['fruits' => 'apples']);

        $forms
            ->checkbox('fruits')
            ->value('apples');

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples">', $content['fruits']->html);

        $forms->validation();

        $content = $forms->getForms();

        $this->assertEquals('<input name="fruits" type="checkbox" value="apples" checked>', $content['fruits']->html);
    }
}