<?php

namespace Tray2\MakeSeeder;

use Tray2\MakeSeeder\Exceptions\ClassNotFoundException;
use Tray2\MakeSeeder\Exceptions\MissingFactoryTraitException;
use Tray2\MakeSeeder\Exceptions\ModelFactoryDoesNotExistException;

class ModelValidator
{

    protected string $model;
    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function validate(): bool
    {
        $this->modelExists()
            ->hasFactoryTrait()
            ->factoryExist();

        return true;
    }

    protected function modelExists(): self
    {
        $model = 'App\Models\\' . $this->model;
        if (! class_exists($model)) {
            throw new ClassNotFoundException($model);
        }
        return $this;
    }

    protected function hasFactoryTrait(): self
    {
        $model = 'App\Models\\' . $this->model;
        $traits = array_keys((new \ReflectionClass($model))->getTraits());
        if (! in_array('Illuminate\Database\Eloquent\Factories\HasFactory', $traits)) {
            throw new MissingFactoryTraitException();
        }
        return $this;
    }

    protected function factoryExist(): self
    {
        $factory = 'Database\Factories\\' . $this->model . 'Factory';

        if (! class_exists($factory)) {
            throw new ModelFactoryDoesNotExistException();
        }
        return $this;
    }
}