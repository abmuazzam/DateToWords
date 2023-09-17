<?php

namespace abmuazzam\DateToWord;

use DateTime;

class DateToWord{
    public static function changeDateToWordsFromDate($date): string
    {
       try{
           $dateString = date('d-F-Y',strtotime($date));
           $date = explode("-",$dateString);
           $firstHalf = self::getDay($date[0]);
           $secondHalf = $date[1];
           $thirdHalf = self::getWord($date[2]);
           return self::replaceAndCapitalize($firstHalf.' '.$secondHalf.' '.$thirdHalf);
       }catch(\TypeError $e){
           return "Date format is invalid. (".$e->getMessage().")";
       }
    }
    public static function changeDateToWords($day,$month,$year): string
    {
        $dateString = date('d-F-Y',strtotime($day.'-'.$month.'-'.$year));
        $date = explode("-",$dateString);
        $firstHalf = self::getDay($day);
        $secondHalf = $date[1];
        $thirdHalf = self::getWord($year);
        return self::replaceAndCapitalize($firstHalf.' '.$secondHalf.' '.$thirdHalf);
    }
    private static function getWord($num): string
    {
        $ones = array(
            0 =>"ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN"
        );
        $tens = array(
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY",
            4 => "FORTY",
            5 => "FIFTY",
            6 => "SIXTY",
            7 => "SEVENTY",
            8 => "EIGHTY",
            9 => "NINETY"
        );
        $hundreds = array(
            "HUNDRED",
            "THOUSAND",
            "MILLION",
            "BILLION",
            "TRILLION",
            "QUADRILLION"
        ); /* limit t quadrillion */
        $num = number_format($num,2,".",",");
        $num_arr = explode(".",$num);
        $wholeNum = $num_arr[0];
        $decNum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholeNum));
        krsort($whole_arr,1);
        $retTxt = "";
        foreach($whole_arr as $key => $i){

            while(str_starts_with($i, "0"))
                $i=substr($i,1,5);
            if($i < 20){
                /* echo "getting:".$i; */
                $retTxt .= $ones[$i];
            }elseif($i < 100){
                if(!str_starts_with($i, "0"))  $retTxt .= $tens[substr($i,0,1)];
                if(substr($i,1,1)!="0") $retTxt .= " ".$ones[substr($i,1,1)];
            }else{
                if(!str_starts_with($i, "0")) $retTxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                if(substr($i,1,1)!="0")$retTxt .= " ".$tens[substr($i,1,1)];
                if(substr($i,2,1)!="0")$retTxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $retTxt .= " ".$hundreds[$key]." ";
            }
        }
        if($decNum > 0){
            $retTxt .= " and ";
            if($decNum < 20){
                $retTxt .= $ones[$decNum];
            }elseif($decNum < 100){
                $retTxt .= $tens[substr($decNum,0,1)];
                $retTxt .= " ".$ones[substr($decNum,1,1)];
            }
        }
        return strtolower($retTxt);
    }
    private static function getDay($day){
        $_day = ["","first","second","third","forth","fifth","sixth","seventh","eighth","ninth","tenth","eleventh","twelfth","thirteenth","fourteenth","fifteenth","sixteenth","seventeenth","eighteenth","nineteenth","twentieth","twenty_first","twenty_second","twenty_third","twenty_forth","twenty_fifth","twenty_sixth","twenty_seventh","twenty_eighth","twenty_ninth","thirty_th","thirty_first"];
        return array_key_exists($day,$_day) ? $_day[$day] : date("dS");
    }
    private static function replaceAndCapitalize($string) : string
    {
        return ucwords(strtolower(str_replace("_"," ",str_replace('-',' ',$string))));
    }
}