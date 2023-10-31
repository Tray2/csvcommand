<?php

namespace Tray2\MakeSeeder\Commands;

use Closure;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tray2\MakeSeeder\CsvParser;
use Tray2\MakeSeeder\Exceptions\ClassNotFoundException;
use Tray2\MakeSeeder\Exceptions\MissingFactoryTraitException;
use Tray2\MakeSeeder\Exceptions\ModelFactoryDoesNotExistException;
use Tray2\MakeSeeder\ModelFinder;
use Tray2\MakeSeeder\ModelValidator;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\warning;

class MakeSeederCommand extends Command implements PromptsForMissingInput
{

    protected $signature = "make:csv-seeder {file : The path to the CSV file}
                                            {model : The model to create the seeder for}
                                            {--no-header? : The CSV doesn't contain headers}
                                            {--separator=, : The separator used}";
    protected $description = 'Converts a CSV file to a Laravel seeder';

    protected string $model = '';
    protected string $file = '';
    protected bool $noHeader = false;
    protected string $separator = ',';

    public function handle(): void
    {
        $this->file = $this->argument('file');
        $this->model = $this->argument('model');
        if ($this->hasOption('no-header')) {
           $this->noHeader = true;
        }
        $this->separator = $this->option('separator');

        if (! $this->validModel()) {
            return;
        }

        $parser = new CsvParser($this->model);
        try {
            $parser->generateFile($this->file. $this->noHeader);
        } catch (FileNotFoundException $e) {
            warning("Could not find {$this->file}.");
            return;
        }

        info("{$this->model}Seeder successfully created in /databases/seeders/{$this->model}Seeder.php.");
    }

    protected function validModel(): bool
    {
        $validator = new ModelValidator($this->model);

        try {
            $validator->validate();
        } catch (ClassNotFoundException $e) {
            warning("Could not find the {$this->model} in the App\\Models namespace." );
            return false;
        } catch (MissingFactoryTraitException $e) {
            warning("The {$this->model} class does not have the HasFactory trait.");
            return false;
        } catch (ModelFactoryDoesNotExistException $e) {
            warning("Could not find the {$this->model}Factory class in the Database\\Factories namespace.");
            return false;
        }

        return true;
    }

    protected function loadModels(): array
    {
        return (new ModelFinder())->find();
    }

    protected function showSelect(InputInterface $input, InputArgument $argument, mixed $label): void
    {
        $options = $this->loadModels();
        $input->setArgument($argument->getName(), select(
            label: $label,
            options: $options
        ));
    }

    protected function showTextInput(InputInterface $input, InputArgument $argument, mixed $label): void
    {
        $input->setArgument($argument->getName(), text(
            label: $label,
            validate: fn($value) => empty($value) ? "The {$argument->getName()} is required." : null,
        ));
    }

    protected function getLabel(InputArgument $argument): mixed
    {
        return $this->promptForMissingArgumentsUsing()[$argument->getName()] ??
            'What is ' . lcfirst($argument->getDescription() ?: ('the ' . $argument->getName())) . '?';
    }

    protected function promptForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $prompted = collect($this->getDefinition()->getArguments())
            ->filter(fn ($argument) => $argument->isRequired() && is_null($input->getArgument($argument->getName())))
            ->filter(fn ($argument) => $argument->getName() !== 'command')
            ->each(function ($argument) use ($input) {
                $label = $this->getLabel($argument);

                if ($label instanceof Closure) {
                    $input->setArgument($argument->getName(), $label());
                    return;
                }

                if (is_array($label)) {
                    [$label, $placeholder] = $label;
                }

                if ($argument->getName() === 'file') {
                    $this->showTextInput($input, $argument, $label);
                } else {
                    $this->showSelect($input, $argument, $label);
                }
            })
            ->isNotEmpty();

        if ($prompted) {
            $this->afterPromptingForMissingArguments($input, $output);
        }
    }

}
