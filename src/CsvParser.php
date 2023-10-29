<?php

namespace Tray2\MakeSeeder;

use function Laravel\Prompts\text;

class CsvParser
{

    protected string $model;
    protected string $destinationDir = __DIR__ . '/../app/database/seeders/';
    protected string $stubsDir = __DIR__ . '/Stubs/Seeder.Stub';
    private int $row = 0;
    protected string $separator = ',';

    public function __construct(string $model, bool $useDefaultDestinationDir = false)
    {
        if (! $useDefaultDestinationDir) {
            $this->destinationDir = base_path('database/seeders/');
        }
        $this->model = $model;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function generateFile(string $csvPath, bool $noHeader = false): void
    {
        $stub = file_get_contents($this->stubsDir);

        $migration = fopen($this->destinationDir . $this->model . 'Seeder.php', 'wb');

        $stub = str_replace(['{Model}', '{Seeder}', '{Rows}'],
                            [$this->model, $this->generateSeeders($csvPath, $noHeader), $this->row], $stub);
        fwrite($migration, $stub );
        fclose($migration);
    }

    protected function generateSeeders(string $csvPath, $noHeader): string
    {
        $fLoader = new FileLoader($csvPath, $this->separator);
        $rows = array_chunk($fLoader->getContent(), $fLoader->columnsCount());

        $seeder = '';
        if ($noHeader) {
            for($i = 0; $i < $fLoader->columnsCount(); $i++) {
                $col = $i + 1;
                $columns[] = text(
                    label: "Enter name for column {$col}",
                    required: true
                );
            }
        } else {
            $columns = array_shift($rows);
        }
        $noOfRows = count($rows);
        $rowIndex = 0;
        $rows = $this->makeAssocArray($columns, $rows);

        foreach($rows as $row) {
            $seeder .= $rowIndex === 0 ?  "[\n" : str_repeat(' ', 16) . "[\n";
            foreach($row as $key => $value) {
                $seeder .= $this->addString($key, $value);
            }
            $seeder .=  str_repeat(' ', 16) . '],';
            $seeder .= $rowIndex +1 < $noOfRows ? "\n" : '';
            $rowIndex++;
        }
        $this->row = $rowIndex;
        return $seeder;
    }

    private function makeAssocArray(array $columns, array $rows): array
    {
        return array_map(fn($row) => array_combine($columns, $row), $rows);
    }

    private function addString(string $key, mixed $value): string
    {
        $col = strtolower($key);
        return str_repeat(' ', 20) . "'{$col}' => '{$value}',\n";
    }

    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }
}
