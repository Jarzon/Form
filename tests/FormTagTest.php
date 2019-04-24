<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Jarzon\Form;

class FormTagTest extends TestCase
{
    public function testFormBaseHtml()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->textarea('test')
            ->min(0)
            ->max(500);

        $this->assertEquals(
            '<form method="POST">',
            $form->getInput('form')->html
        );
    }

    public function testFormActionHtml()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->action('/targetPage.php')

            ->textarea('test')
            ->min(0)
            ->max(500);

        $this->assertEquals(
            '<form method="POST" action="/targetPage.php">',
            $form->getInput('form')->html
        );
    }

    public function testFormTargetHtml()
    {
        $form = new Form(['test' => 'a']);

        $form
            ->target('/targetPage.php')

            ->textarea('test')
            ->min(0)
            ->max(500);

        $this->assertEquals(
            '<form method="POST" target="_self">',
            $form->getInput('form')->html
        );
    }
}