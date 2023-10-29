<?php

use Tray2\MakeSeeder\ModelFinder;

it('finds all the models in the App\Models namespace', function () {
    $expected = ['Book', 'BookNoFactory', 'BookNoTrait'];
    $actual = (new ModelFinder())->find();
    expect($actual)->toEqual($expected);
});

it('finds all the models in a specified namespace', function () {
    $expected = ['Customer', 'CustomerBank'];
    $actual = (new ModelFinder('App\Models\Customers'))->find();
    expect($actual)->toEqual($expected);
});
