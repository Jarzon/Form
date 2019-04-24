# Form

## Install

```
composer require jarzon/form
```

## Usage

Build the form
```php
<?php
$form = new Jarzon\Form($_POST);

// Create your form
$form
  ->text('name')
  ->min(2)
  ->max(100)
  ->required()
  ->placeholder('Joe Doe')

  ->number('age')
  ->min(0)
  ->max(100)
  
  ->submit();
```

Show the form in the view
```php
<?=$form('form')->html?>

    <div><?=$form('name')->label('Name:')->row?></div>
    
    <div><?=$form('age')->label('Age:')->row?></div>
    
    <?=$form('submit')->value('Save')->html?>

<?=$form('/form')->html?>
```

Process the form values
```php
<?php
// On submit validate the form values
if($form->submitted()) {
    try {
        // Does the validation based on the inputs types, min/max, required
        if($values = $form->validation()) {
            // Do what you want with the returned values
            echo "Your name is {$values['name']}";
        }
    }
    catch (\Jarzon\Form\ValidationException $e) {
        // ->validation() throw a custom Exception if there is an invalid value
        echo "Error: {$e->getMessage()}";
    }
}
```