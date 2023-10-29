<?php

use Tray2\MakeSeeder\Exceptions\ClassNotFoundException;
use Tray2\MakeSeeder\Exceptions\MissingFactoryTraitException;
use Tray2\MakeSeeder\Exceptions\ModelFactoryDoesNotExistException;
use Tray2\MakeSeeder\ModelValidator;

it('throws ClassNotFoundException if the Model does not exist', function () {
    $modelValidator = new ModelValidator('NotExistingModel');
    $modelValidator->validate();
})->throws(ClassNotFoundException::class);

it('returns true if the model is valid', function () {
    $modelValidator = new ModelValidator('Book');
    expect($modelValidator->validate())->toBeTrue();
});

it('throws MissingFactoryTraitException if the model does not has the HasFactory trait', function () {
    $modelValidator = new ModelValidator('BookNoTrait');
    $modelValidator->validate();
})->throws(MissingFactoryTraitException::class);

it('throws a ModelFactoryDoesNotExistException if the models factory class is missing', function () {
    $modelValidator = new ModelValidator('BookNoFactory');
    $modelValidator->validate();
})->throws(ModelFactoryDoesNotExistException::class);
