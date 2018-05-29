<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Mock\Forms;

class TextInputTest extends TestCase
{
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

        $forms->validation();
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

        $forms->validation();
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

        $forms->validation();
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

        $this->assertEquals('<input name="test" type="text" minlength="4" maxlength="10" class="testClass secondClass" custom-attr="customValue">', $content['test']->html);
        $this->assertEquals('<input name="test2" type="text">', $content['test2']->html);

        $this->assertEquals('test', $content['test']->label);

        $html = '';

        foreach ($content as $form) {
            $html .= "<label>{$form->label}<br>{$form->html}</label>";
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

        $this->assertEquals(null, $content['test']->getLabel());
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

        $this->assertEquals('<input name="test" type="text" value="wrong" minlength="4" maxlength="10">', $content['test']->getHtml());

        $forms->validation();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']->getHtml());
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

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']->getHtml());
    }
}