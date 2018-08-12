<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class DateInputTest extends TestCase
{
    public function testValidDate()
    {
        $forms = new Form(['test' => '2014-12-04']);

        $forms
            ->date('test');

        $values = $forms->validation();

        $this->assertEquals(['test' => '2014-12-04'], $values);
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is not a valid date
     */
    public function testInvalidDate()
    {
        $forms = new Form(['test' => '0000-00-00']);

        $forms
            ->date('test');

        $forms->validation();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is lower that 2000-01-01
     */
    public function testMinDate()
    {
        $forms = new Form(['test' => '1991-01-01']);

        $forms
            ->date('test')
            ->min('2000-01-01');

        $forms->validation();
    }

    /**
     * @expectedException     \Exception
     * @expectedExceptionMessage test is higher that 2000-01-01
     */
    public function testMaxDate()
    {
        $forms = new Form(['test' => '2005-01-01']);

        $forms
            ->date('test')
            ->max('2000-01-01');

        $forms->validation();
    }

    public function testDate()
    {
        $forms = new Form(['test' => '2005-12-28']);

        $forms
            ->date('test')
            ->min('2000-01-01')
            ->max('2010-01-01');

        $result = $forms->validation();

        $this->assertEquals($result, ['test' => '2005-12-28']);
    }

    public function testGetFormsDate() {
        $forms = new Form(['test' => 'a']);

        $forms
            ->date('test');

        $content = $forms->getForms();

        $this->assertEquals('<input name="test" type="date">', $content['test']->html);
    }
}