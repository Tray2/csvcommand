<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Tray2\MakeSeeder\FileLoader;

it('loads the file', function () {
    $expected = [
        'Name','Email',
        'Tray2','tray2@example.com'
    ];
    $path = __DIR__ . '/../Stubs/Csvs/name-email-single-row.csv';
    $fileLoader = new FileLoader($path);
    expect($fileLoader->getContent())->toEqual($expected);
});

it('throws FileNotFoundException if the file does not exist', function () {
    $path = __DIR__ . '/../Stubs/Csvs/i-dont-exist.csv';
    $fileLoader = new FileLoader($path);
    $content = $fileLoader->getContent();
})->throws(FileNotFoundException::class);

it('knows how many columns the csv has', function () {
    $expected = 2;
    $path = __DIR__ . '/../Stubs/Csvs/name-email-single-row.csv';
    $fileLoader = new FileLoader($path);
    $fileLoader->getContent();
    expect($fileLoader->columnsCount())->toEqual($expected);
});

it('can load files with other separators than commas', function () {
    $expected = [
        'Name','Email',
        'Tray2','tray2@example.com'
    ];
    $path = __DIR__ . '/../Stubs/Csvs/name-email-single-row-semi-colon-separator.csv';
    $fileLoader = new FileLoader($path, ';');
    expect($fileLoader->getContent())->toEqual($expected);
});

