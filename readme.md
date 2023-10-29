## CsvCommand

CsvCommand is a package for Laravel that converts a CSV file into a Laravel database seeder class.

It is to be used as a dev dependency, and is not intended to be used in production to import csv files
into your application.

It is not yet added to packigist, but you can still manually install it if you want.
**Beware!** That it is still in beta state, so please report any bugs found.

### Use

`php artisan make:csv-seeder <csv-file> <model> --noheader --separator=,`

It is assumed that the csv has a header row, if it doesn't pass the `--noheader` flag.

It is also assumed that the separator is a comma, if it is not pass the `--separator=<separator>` flag.

If no csv file, or model given it will prompt you for the file, and show you a list of available models.

It is for now assumed that your column names match the headers in the csv file. The plan is to add another flag
to prompt you for the correct headers and override the ones in the file.

The model you choose must use the `HasFactory` trait, and it also must have a corresponding factory file.

```
make:csv-seeder {file : The path to the CSV file}
                {model : The model to create the seeder for}
                {--no-header? : The CSV doesn't contain headers}
                {--separator=, : The separator used}";
```

