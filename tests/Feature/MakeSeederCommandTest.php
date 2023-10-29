<?php

use Tests\PackageTestCase;
use Tray2\MakeSeeder\Commands\MakeSeederCommand;

uses(PackageTestCase::class)->in(__DIR__);

it('can run the command successfully', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => __DIR__ . '/../Stubs/Csvs/name-email-country-drink-four-row.csv',
            'model' => 'Book'
        ]
    )->assertSuccessful()
    ->doesntExpectOutputToContain('Could not find name-email-country-drink-four-row.csv')
    ->expectsOutputToContain("BookSeeder successfully created in /databases/seeders/BookSeeder.php.");
    unlink(base_path('/database/seeders/BookSeeder.php'));
});

it('can run the command successfully on csv with other separators', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => __DIR__ . '/../Stubs/Csvs/name-email-single-row-semi-colon-separator.csv',
            'model' => 'Book',
            '--separator' => ','
        ]
    )->assertSuccessful()
        ->doesntExpectOutputToContain('Could not find name-email-single-row-semi-colon-separator.csv')
        ->expectsOutputToContain("BookSeeder successfully created in /databases/seeders/BookSeeder.php.");
    unlink(base_path('/database/seeders/BookSeeder.php'));
});

it('shows a prompt warning when the given model does not exist', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => 'some.csv',
            'model' => 'NotExistingModel'
        ]
    )->expectsOutputToContain('Could not find the NotExistingModel in the App\\Models namespace.');
});

it('shows a prompt warning when the given model does not have the HasFactory trait', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => 'some.csv',
            'model' => 'BookNoTrait'
        ]
    )->expectsOutputToContain('The BookNoTrait class does not have the HasFactory trait.');
});

it('shows a prompt warning when the given models factory does not exist', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => 'some.csv',
            'model' => 'BookNoFactory'
        ]
    )->expectsOutputToContain('Could not find the BookNoFactoryFactory class in the Database\\Factories namespace.');
});

it('shows a prompt warning when the given file name does not exist', function () {
    $this->artisan(
        MakeSeederCommand::class, [
            'file' => 'some.csv',
            'model' => 'Book'
        ]
    )->expectsOutputToContain('Could not find some.csv.');
});
