# Form

## Install

```
composer require jarzon/form
```

## Usage

```php
<?php
$form = new Jarzon\Form($_POST);

// Create your form
$form
  ->text('name')
  ->min(2)
  ->max(100)
  ->placeholder('Joe Doe')

  ->number('age')
  ->min(0)
  ->max(100)
  
  ->submit();


// List all the inputs in the view
foreach ($form->getForms() as $i):?>
    <?=$i->row?>
<?php endforeach;

// Or build the form manually
?>

<?=$form('form')->html?>

    <div><?=$form('name')->label('Name:')->row?></div>
    
    <div><?=$form('age')->label('Age:')->row?></div>
    
    <?=$form('submit')->value('Save')->html?>

<?=$form('/form')->html?>

<?php
// You can also use static method like so
$form->getInput('name')->getRow();

// On submit validate the form values
if($form->submitted()) {
    try {
        // Does the inputs validation based on their types
        if($values = $form->validation()) {
            // Do what you want with values
        }
    }
    catch (Exception $e) {
        // ->validation() throw Exception if the is invalid values
    }
}
```