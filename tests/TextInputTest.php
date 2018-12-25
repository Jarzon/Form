<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class TextInputTest extends TestCase
{
    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is too short
     */
    public function testLengthLowerThatMin()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->text('test')
            ->min(4);

        $forms->validation();
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is required
     */
    public function testLengthNull()
    {
        $forms = new Form(['test' => '']);

        $forms
            ->text('test')
            ->min(4)
            ->max(10)
            ->required();

        $forms->validation();
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is too long
     */
    public function testLengthHigherThatMax()
    {
        $forms = new Form(['test' => '123456789ab']);

        $forms
            ->text('test')
            ->max(10);

        $forms->validation();
    }

    public function testGetFormsText()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->text('test')
            ->label('Test:')
            ->min(4)
            ->max(10)
            ->class('testClass secondClass')
            ->attributes(['custom-attr' => 'customValue']);

        $forms
            ->text('test2')

            ->submit();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" id="test" minlength="4" maxlength="10" class="testClass secondClass" custom-attr="customValue">', $content['test']->html);
        $this->assertEquals('<input name="test2" type="text">', $content['test2']->html);

        $this->assertEquals('<label for="test">Test:</label>', $content['test']->label);

        $html = '';

        foreach ($content as $form) {
            $html .= "{$form->label}{$form->html}";
        }

        $this->assertEquals('<form method="POST"><label for="test">Test:</label><input name="test" type="text" id="test" minlength="4" maxlength="10" class="testClass secondClass" custom-attr="customValue"><input name="test2" type="text"><input type="submit" name="submit"></form>', $html);
    }

    public function testNoLabel()
    {
        $forms = new Form(['test' => 'a']);

        $forms
            ->text('test')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals(null, $content['test']->label);
    }

    public function testUpdateValue()
    {
        $forms = new Form(['test' => 'good']);

        $forms
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="wrong" minlength="4" maxlength="10">', $content['test']->html);

        $forms->validation();

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']->html);
    }

    public function testUpdateUnexistingValue()
    {
        $forms = new Form(['test' => 'good']);

        $forms
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $forms->updateValues(['test' => 'good', 'asdf' => 'asdf']);

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="text" value="good" minlength="4" maxlength="10">', $content['test']->html);
    }

    public function testRepeated()
    {
        $form = new Form(['test' => ['1234', 'NaN']]);

        $form->repeat()
            ->text('test');

        $values = $form->validation();

        $this->assertEquals(['1234', 'NaN'], $values['test']);
    }
}