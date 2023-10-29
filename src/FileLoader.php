<?php

namespace Tray2\MakeSeeder;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FileLoader
{
    protected string $path;
    protected int $columns = 0;
    protected array $csv = [];
    protected string $separator;

    public function __construct(string $path, string $separator = ',')
    {
        $this->path = $path;
        $this->separator = $separator;
    }

    /**
     * @throws FileNotFoundException
     */
    public function getContent(): array
    {
        if (($handle = @fopen($this->path, 'rb')) !== false) {
            while (($data = fgetcsv($handle, 1000, $this->separator)) !== false) {
                if (count($this->csv) === 0) {
                    $this->columns = count($data);
                }
                $this->csv = array_merge($this->csv, $data);
            }
        }

        if (! $handle) {
            throw new FileNotFoundException();
        }
        fclose($handle);
        return $this->csv;
    }

    public function columnsCount(): int
    {
        return $this->columns;
    }
}