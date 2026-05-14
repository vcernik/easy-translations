<?php
namespace vcernik\EasyTranslations;

use Nette\Neon\Neon;
use Spatie\SimpleExcel\SimpleExcelReader;
use Nette\Utils\Arrays;


class ExcelToNeon{
    private $output_array;
    private $empty_strings;


    /**
     * @param string $file
     * @param string $output_folder
     * @param bool $empty_strings
     * @param array $columnNames ['domain' => 'domain', 'id' => 'id']
     */
    public function convert($file, $output_folder, $empty_strings = false, $columnNames = ['domain' => 'domain', 'id' => 'id']) {
        $this->empty_strings = $empty_strings;
        $this->output_array = [];

        $domainCol = $columnNames['domain'] ?? 'domain';
        $idCol = $columnNames['id'] ?? 'id';

        $rows = SimpleExcelReader::create($file)->getRows();

        $rows->each(function(array $row) use ($domainCol, $idCol) {
            if (!isset($row[$domainCol]) || !isset($row[$idCol])) {
                // přeskoč řádek pokud chybí doména nebo id
                return;
            }

            if (!isset($this->output_array[$row[$domainCol]])) {
                $this->output_array[$row[$domainCol]] = [];
            }

            foreach ($row as $column => $value) {
                if ($column !== $domainCol && $column !== $idCol) {
                    //jde o sloupec s překlady
                    $lang = $column;

                    //ověřím jestli je daný jazyk založen
                    if (!isset($this->output_array[$row[$domainCol]][$lang])) {
                        $this->output_array[$row[$domainCol]][$lang] = [];
                    }

                    //přidám záznam pouze pokud není hodnota prázdná nebo je povoleno ukládat prázdné řetězce
                    if (
                        ($this->empty_strings && $value !== null) ||
                        (!$this->empty_strings && $value !== null && $value !== '')
                    ) {
                        $array = $this->makeArray(explode('.', $row[$idCol]), $value);
                        $this->output_array[$row[$domainCol]][$lang] = Arrays::mergeTree($this->output_array[$row[$domainCol]][$lang], $array);
                    }
                }
            }
        });

        // vytvoření složky pokud neexistuje
        if (!is_dir($output_folder)) {
            mkdir($output_folder, 0777, true);
        }

        //creating neon files
        foreach ($this->output_array as $file => $rest) {
            foreach ($rest as $language => $array) {
                if (!empty($array)) {
                    $neon = Neon::encode($array, blockMode: true);
                    file_put_contents($output_folder . '/' . $file . '.' . $language . '.neon', $neon);
                }
            }
        }
    }



    /**
     * Create multidimensional array
     * credits: https://www.daniweb.com/programming/web-development/threads/476988/create-multidimensional-array-from-array-of-keys-and-a-value
     */
    private function makeArray($keys, $value)  {
        $var=array();   
        $index=array_shift($keys);
        if (!isset($keys[0]))
            {
            $var[$index]=$value;
            }
        else 
            {   
            $var[$index]=$this->makeArray($keys,$value);            
            }
        return $var;
    }

   
}

     

