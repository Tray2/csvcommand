<?php

namespace Tray2\MakeSeeder;

class ModelFinder
{
    protected  string $modelPath = __DIR__ .'/../app/Models';

    public function __construct(string $modelPath = '')
    {
        if ($modelPath !== '') {
            $this->modelPath = __DIR__ . '/../' . str_replace('\\', '/', $modelPath);
        }
    }

    public function find(): array
    {
        $files =  array_values(array_filter(array_map( function (string $file) {
            if (str_ends_with($file, '.php')) {
                return str_replace('.php', '', $file);
            }
        }, scandir($this->modelPath))));
        return $files;
    }

}