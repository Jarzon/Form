<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\ValidationException;
use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class DateInputTest extends TestCase
{
    public function testValidDate()
    {
        $form = new Form(['test' => '2014-12-04']);

        $form
            ->date('test');

        $values = $form->validation();

        $this->assertEquals(['test' => '2014-12-04'], $values);
    }

    public function testInvalidDate()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is not a valid date');

        $form = new Form(['test' => '0000-00-00']);

        $form
            ->date('test');

        $form->validation();
    }

    public function testMinDate()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is lower that 2000-01-01');

        $form = new Form(['test' => '1991-01-01']);

        $form
            ->date('test')
            ->min('2000-01-01');

        $form->validation();
    }

    public function testMaxDate()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('test is higher that 2000-01-01');

        $form = new Form(['test' => '2005-01-01']);

        $form
            ->date('test')
            ->max('2000-01-01');

        $form->validation();
    }

    public function testDate()
    {
        $form = new Form(['test' => '2005-12-28']);

        $form
            ->date('test')
            ->min('2000-01-01')
            ->max('2010-01-01');

        $result = $form->validation();

        $this->assertEquals($result, ['test' => '2005-12-28']);
    }

    public function testGetFormsDate() {
        $form = new Form(['test' => 'a']);

        $form
            ->date('test');

        $this->assertEquals(
            '<input name="test" type="date">',
            $form->getInput('test')->html
        );
    }
}