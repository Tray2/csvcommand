<?php

use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;
use Tray2\MakeSeeder\CsvParser;

it('stores the model name', function () {
    $expected = 'Book';
    $cParser = new CsvParser('Book', true);
    expect($cParser->getModel())->toEqual($expected);
})->isInIsolation();

it('can handle files with no headers', function () {
    Prompt::fake([
        'n', 'a', 'm', 'e', Key::ENTER,
        'e', 'm', 'a', 'i', 'l', Key::ENTER,
    ]);
    $noHeader = true;
    $expected = file_get_contents(__DIR__ . '/../Stubs/Seeders/name-email-single-row.stub');

    $seeder = __DIR__ . '/../../app/database/seeders/BookSeeder.php';
    $csv = __DIR__ . '/../Stubs/Csvs/name-email-single-row-no-headers.csv';
    $cParser = new CsvParser('Book', true);
    $cParser->generateFile($csv, $noHeader);
    expect(file_get_contents($seeder))->toEqual($expected);
    unlink($seeder);
    Prompt::assertStrippedOutputContains('Enter name for column');
});

it('generates seeder code for a file with two columns and one row', function () {
    $expected = file_get_contents(__DIR__ . '/../Stubs/Seeders/name-email-single-row.stub');

    $seeder = __DIR__ . '/../../app/database/seeders/BookSeeder.php';
    $csv = __DIR__ . '/../Stubs/Csvs/name-email-single-row.csv';
    $cParser = new CsvParser('Book', true);
    $cParser->generateFile($csv);
    expect(file_get_contents($seeder))->toEqual($expected);
    unlink($seeder);
});

it('generates seeder code for a file with two columns and two rows', function () {
    $expected = file_get_contents(__DIR__ . '/../Stubs/Seeders/name-email-two-row.stub');

    $seeder = __DIR__ . '/../../app/database/seeders/BookSeeder.php';
    $csv = __DIR__ . '/../Stubs/Csvs/name-email-two-row.csv';
    $cParser = new CsvParser('Book', true);
    $cParser->generateFile($csv);
    expect(file_get_contents($seeder))->toEqual($expected);
    unlink($seeder);
});

it('generates seeder code for a file with four columns and four rows', function () {
    $expected = file_get_contents(__DIR__ . '/../Stubs/Seeders/name-email-country-drink-four-row.stub');

    $seeder = __DIR__ . '/../../app/database/seeders/BookSeeder.php';
    $csv = __DIR__ . '/../Stubs/Csvs/name-email-country-drink-four-row.csv';
    $cParser = new CsvParser('Book', true);
    $cParser->generateFile($csv);
    expect(file_get_contents($seeder))->toEqual($expected);
    unlink($seeder);
});

it('can handle other separators than commas', function () {
    $expected = file_get_contents(__DIR__ . '/../Stubs/Seeders/name-email-single-row.stub');

    $seeder = __DIR__ . '/../../app/database/seeders/BookSeeder.php';
    $csv = __DIR__ . '/../Stubs/Csvs/name-email-single-row-semi-colon-separator.csv';
    $cParser = new CsvParser('Book', true);
    $cParser->setSeparator(';');
    $cParser->generateFile($csv);
    expect(file_get_contents($seeder))->toEqual($expected);
    unlink($seeder);
});

