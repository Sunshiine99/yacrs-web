<?php

class StopWords
{
    const words = array("a", "about", "all", "am", "an", "and", "any", "are", "aren", "as", "at",
    "be", "been", "being", "below", "between", "both", "but", "by", "can", "cannot",
    "could", "couldn", "did", "didn", "do", "does", "doesn't", "doing", "don",
    "down", "each", "few", "for", "from", "further", "had", "hadn", "has", "hasn",
    "have", "haven", "having", "he", "he", "he", "he", "her", "here", "here", "on", "only",
    "hers", "herself", "him", "himself", "his", "how", "i", "not", "of", "off",
    "if", "in", "into", "is", "isn", "it", "it", "its", "itself", "let", "me", "more",
    "most", "mustn", "my", "m", "s", "t", "d", "ll", "ve", "re", "myself", "no", "nor",
    "or", "other", "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan",
    "she", "she", "she", "she", "should", "shouldn", "so", "some", "such", "through", "to",
    "there", "there", "these", "they", "this", "those",
    "we", "were", "weren", "what", "what s", "when",
    "where", "which", "too", "up", "while", "who", "whom", "why", "with",
    "would", "wouldn", "you", "was", "wasn",
    "your", "yours", "yourself", "yourselves", "than", "that", "the", "their",
    "theirs", "them", "themselves", "then");


    public static function isInStop($word){
        foreach(self::words as $str){
            if(strcmp($word, $str) == 0) return true;
        }
        return false;
    }

    public static function removeStop($dict){
        $arr = [];
        foreach($dict as $key => $value) {
            if(!self::isInStop($key)){
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public static function getStop(){
        return self::words;
    }
}