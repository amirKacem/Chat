<?php


namespace MyChat\Helpers;

class Format{
    public function formatDate($date){
        return date('d/m/Y H:i:s ', strtotime($date));
    }

    public function textShorten($text, $limit = 400){
        $text = $text. " ";
        $text = substr($text, 0, $limit);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text.".....";
        return $text;
    }

    public function validation($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function allvalidation($data){

        foreach ($data as $key => $value) {
            trim($value);
            stripcslashes($value);
            htmlspecialchars($value);

        }


        return $data;
    }



}