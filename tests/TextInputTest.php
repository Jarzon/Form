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
        $form = new Form(['test' => 'a']);

        $form
            ->text('test')
            ->min(4);

        $form->validation();
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is required
     */
    public function testLengthNull()
    {
        $form = new Form(['test' => '']);

        $form
            ->text('test')
            ->min(4)
            ->max(10)
            ->required();

        $form->validation();
    }

    /**
     * @expectedException     \Jarzon\ValidationException
     * @expectedExceptionMessage test is too long
     */
    public function testLengthHigherThatMax()
    {
        $form = new Form(['test' => '123456789ab']);

        $form
            ->text('test')
            ->max(10);

        $form->validation();
    }

    public function testGetFormsText()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->text('test')
            ->label('Test:')
            ->min(4)
            ->max(10)
            ->class('testClass secondClass')
            ->attributes(['custom-attr' => 'customValue']);

        $form
            ->text('test2')

            ->submit();

        $content = $form->getForms();

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
        $form = new Form(['test' => 'a']);

        $form
            ->text('test')
            ->min(4)
            ->max(10);

        $this->assertEquals(null, $form->getInput('test')->label);
    }

    public function testUpdateValue()
    {
        $form = new Form(['test' => 'good']);

        $form
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $this->assertEquals(
            '<input name="test" type="text" value="wrong" minlength="4" maxlength="10">',
            $form->getInput('test')->html
        );

        $form->validation();

        $this->assertEquals(
            '<input name="test" type="text" value="good" minlength="4" maxlength="10">',
            $form->getInput('test')->html
        );
    }

    public function testUpdateUnexistingValue()
    {
        $form = new Form(['test' => 'good']);

        $form
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $form->updateValues(['test' => 'good', 'asdf' => 'asdf']);

        $this->assertEquals(
            '<input name="test" type="text" value="good" minlength="4" maxlength="10">',
            $form->getInput('test')->html
        );
    }

    public function testRepeated()
    {
        $form = new Form(['test' => ['1234', 'NaN']]);

        $form->repeat()
            ->text('test');

        $values = $form->validation();

        $this->assertEquals([['test' => '1234'], ['test' => 'NaN']], $values);
    }

    public function shouldNotReturnValueWhenItsTheSameAsUpdateValues()
    {
        $form = new Form(['test' => 'good']);

        $form
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $form->updateValues(['test' => 'good']);

        $values = $form->validation();

        $this->assertEquals([], $values);
    }

    public function shouldReturnValueWhenItsTheSameAsDefaultValue()
    {
        $form = new Form(['test' => 'test']);

        $form
            ->text('test')
            ->value('wrong')
            ->min(4)
            ->max(10);

        $values = $form->validation();

        $this->assertEquals(['test' => 'test'], $values);
    }
}