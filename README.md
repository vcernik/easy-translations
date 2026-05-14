# Easy Translations
Converting translation NEON files to Excel and back (Excel to NEON)

## Install
To install the latest version of `vcernik/easy-translations` use [Composer](https://getcomposer.com).

    composer require vcernik/easy-translations

## Converting NEON files to Excel
You can convert to .xlsx or .csv file (it depends just on extension).

    use vcernik\EasyTranslations\EasyTranslations;
    EasyTranslations::NeonToExcel('FOLDER WITH NEON FILES','output.xlsx');
    
## Converting Excel to NEON

You can convert from .xlsx or .csv file (it depends just on extension).

    use vcernik\EasyTranslations\EasyTranslations;
    EasyTranslations::ExcelToNeon('input.xlsx','OUTPUT FOLDER');

If you want to allow empty strings in output NEON files:

    EasyTranslations::ExcelToNeon('input.xlsx','OUTPUT FOLDER', true);

### Custom column names

If your Excel/CSV file uses different column names for domain and id, you can specify them using the 4th parameter:

    EasyTranslations::ExcelToNeon('input.xlsx', 'OUTPUT FOLDER', columnNames: [
        'domain' => 'modul',
        'id' => 'klic',
    ]);

This will use 'modul' instead of 'domain' and 'klic' instead of 'id' as the key columns.


## Develop

Test: ddev php  vendor/bin/tester test