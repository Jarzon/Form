<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class FormTest extends TestCase
{
    public function testGetFormHtml()
    {
        $form = new Form([]);

        $form
            ->text('username')
            ->min(4)
            ->max(10)

            ->password('password')
            ->min(10)
            ->max(100)

            ->submit()
            ->value('Save');

        $tags = $form->getForms();

        $output = '';

        foreach ($tags as $tag) {
            $output .= $tag->html;
        }

        $this->assertEquals(
            '<form method="POST"><input name="username" type="text" minlength="4" maxlength="10"><input name="password" type="password" minlength="10" maxlength="100"><input type="submit" name="submit" value="Save"></form>',
            $output
        );
    }

    public function testInputRowAttribute()
    {
        $form = new Form([]);

        $form
            ->text('username')
            ->min(4)
            ->max(10)

            ->password('password')
            ->min(10)
            ->max(100)

            ->submit()
            ->value('Save');

        $tags = $form->getForms();

        $output = '';

        foreach ($tags as $tag) {
            $output .= $tag->row;
        }

        $this->assertEquals(
            '<form method="POST"><input name="username" type="text" minlength="4" maxlength="10"><input name="password" type="password" minlength="10" maxlength="100"><input type="submit" name="submit" value="Save"></form>',
            $output
        );
    }

    public function testFormSubmited()
    {
        $form = new Form(['username' => 'Joe Doe', 'submit' => 'Save']);

        $form
            ->text('username')
            ->min(4)
            ->max(10)

            ->submit()->value('Save');

        $form->getForms();

        $this->assertEquals(true, $form->submitted());
    }
}