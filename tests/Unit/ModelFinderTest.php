<?php

use Tray2\MakeSeeder\ModelFinder;

it('finds all the models in the app\Models path', function () {
    $expected = ['Book', 'BookNoFactory', 'BookNoTrait'];
    $actual = (new ModelFinder())->find();
    expect($actual)->toEqual($expected);
});

it('finds all the models in a specified path', function () {
    $expected = ['Customer', 'CustomerBank'];
    $actual = (new ModelFinder('app\Models\Customers'))->find();
    expect($actual)->toEqual($expected);
});
