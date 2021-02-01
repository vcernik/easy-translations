<?php
namespace vcernik\EasyTranslations;

final class EasyTranslations
{
	/**
     * Convert NEON files in folder to Excel file
     * @outputFile can be *.csv or *.xlsx
     */
	public static function NeonToExcel(string $neonFilesFolder,string $outputFile)
	{
        $easy=new NeonToExcel;
        $easy->convert($neonFilesFolder,$outputFile);
	}


	/**
	 * Covert Excel file to NEON files
     * $emptyStrings=true possible leads to empty string in final NEON files
	 */
	public static function ExcelToNeon(string $inputFile,string $outputFolder,bool $emptyStrings=false)
	{
        $easy=new ExcelToNeon;
        $easy->convert($inputFile,$outputFolder,$emptyStrings);
	}
}