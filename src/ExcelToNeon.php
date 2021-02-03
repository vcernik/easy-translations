<?php
namespace vcernik\EasyTranslations;

use Nette\Neon\Neon;
use Spatie\SimpleExcel\SimpleExcelReader;
use Nette\Utils\Arrays;


class ExcelToNeon{
    private $output_array;
    private $empty_strings;

    public function convert($file,$output_folder,$empty_strings=false){
        $this->empty_strings=$empty_strings;
        $this->output_array=[];

        $rows = SimpleExcelReader::create($file)->getRows();
            
        $rows->each(function(array $row) {
            
            if(!isset($this->output_array[$row['domain']])){
                $this->output_array[$row['domain']]=[];
            }

            foreach($row as $column=>$value){
                if($column!="domain" and $column!="id"){
                    //jde o sloupec s překlady
                    $lang=$column;

                    //ověřím jestli je daný jazyk založen
                    if(!isset($this->output_array[$row['domain']][$lang])){
                        $this->output_array[$row['domain']][$lang]=[];
                    }

                    //přidám záznam
                    if($value!=null or $this->empty_strings){
                        $array=$this->makeArray(explode(".",$row['id']),$value);
                        
                        $this->output_array[$row['domain']][$lang]=Arrays::mergeTree($this->output_array[$row['domain']][$lang], $array);                    
                    }
                }
            }

        });

        //creating neon files
        foreach($this->output_array as $file=>$rest){
            foreach($rest as $language=>$array){
                if(!empty($array)){
                    $neon=Neon::encode($array, Neon::BLOCK); 
                    file_put_contents($output_folder.'/'.$file.'.'.$language.'.neon',$neon);
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

     

