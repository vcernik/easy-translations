<?php
namespace vcernik\EasyTranslations;

use Nette\Neon\Neon;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Nette\Utils\Arrays;


final class NeonToExcel{

    private $languages=[];

    public function convert(string $folder,$output)
    {
        $files_grouped=$this->loadNeonFiles($folder,$output);
        $final=$this->transformArray($files_grouped);
        $this->renderExcel($final,$output);        
    }
    /**
     * Load and parse files in folder
     * generating list of languages ($this->languages)
     * output array structure: title->language->id->string
     */
    private function loadNeonFiles(string $folder,$output){
        $files = glob($folder.'/*.neon');
        $files_grouped=[];
        foreach($files as $file){
            $neon=Neon::decode(file_get_contents($file));

            if($neon!==null){
                $exploded_path=explode("/",$file);
                $filename=$exploded_path[count($exploded_path)-1];

                $exploded_name=explode(".",$filename);
                $title=$exploded_name[0];
                $language=$exploded_name[1];

                //přidám do seznamu dostupných jazyků
                if(!in_array($language,$this->languages)){
                    $this->languages[]=$language;
                }

                $files_grouped[$title][$language]=$this->parseMultiNeon($neon);
            
            }
        }
        
        return $files_grouped;
    }

    /**
     * Transform structure of array
     * from: title->language->id->string
     * into: title->id->language->string
     */
    private function transformArray($files_grouped){
        $final=[];
        foreach($files_grouped as $title=>$temp){
            $final[$title]=[];
            foreach($this->languages as $language){
                $last_id=false;
                //projde jednotlivé záznamy v daném jazyce
                foreach($temp[$language] as $id=>$value){
                    
                    //pokud ještě nějaké ID není zadefinováno..
                    if(!isset($final[$title][$id])){
                        //umístím jej na správnou pozici
                        if($last_id){
                            Arrays::insertAfter($final[$title], $last_id, [$id=>[]]);
                        }
                        else{
                            $final[$title][$id]=[];
                        }
                    }

                    //zapíšu překlad
                    $final[$title][$id][$language]=$value;

                    $last_id=$id;
                }
            }
        }

        return $final;
    }


    /**
     * Final - render Excel file
     */
    private function renderExcel($finalArray,$outputFile){
        $writer = SimpleExcelWriter::create($outputFile);
        foreach($finalArray as $title=>$section){
            foreach($section as $id=>$row){
                $excelrow=[
                    'domain' => $title,
                    'id' => $id
                ];
                foreach($this->languages as $language){
                    if(isset($row[$language])){
                        $excelrow[$language]=$row[$language];
                    }
                    else{
                        $excelrow[$language]="";
                    }
                    

                }
                $writer->addRow($excelrow);
            }
        }
    }


    /**
     * Convert multidimensional array into: xx.yy.zz => (string) value
     */
    private function parseMultiNeon(array $array){
        $translations=[];

        foreach($this->getKeys($array) as $key){
            $findvalue=$array;
            foreach(explode(".",$key) as $subkey){
                $findvalue=$findvalue[$subkey];
            }
            $translations[$key]=$findvalue;
        }
        return $translations;
    }

    /**
     * Get keys of multidimensional array like xx.yy.zz
     */
    private function getKeys(array $array){
        $keys=[];
        foreach(array_keys($array) as $key){
            
            if(is_array($array[$key])){
                foreach($this->getKeys($array[$key]) as $sub){
                    $keys[]=$key.".".$sub;
                }
            }
            else{
                $keys[]=$key;
            }
        }
        return $keys;
    }
}

     

