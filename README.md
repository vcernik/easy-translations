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

In case of empty strings, by default it will not generate empty line in neon file.
You can change it by this:

    EasyTranslations::ExcelToNeon('input.xlsx','OUTPUT FOLDER', true);
