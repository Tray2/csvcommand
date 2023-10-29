<?php

namespace Tray2\MakeSeeder;

class ModelFinder
{
    protected  string $namespace = __DIR__ .'/../app/Models';

    public function __construct(string $namespace = '')
    {
        if ($namespace !== '') {
            $this->namespace = __DIR__ . '/../' . str_replace('\\', '/', $namespace);
        }
    }

    public function find(): array
    {
        $files =  array_values(array_filter(array_map( function (string $file) {
            if (str_ends_with($file, '.php')) {
                return str_replace('.php', '', $file);
            }
        }, scandir($this->namespace))));
        return $files;
    }

}