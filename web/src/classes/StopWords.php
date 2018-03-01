<?php

class StopWords
{
    const words = array("a", "about", "all", "am", "an", "and", "any", "are", "aren't", "as", "at",
    "be", "been", "being", "below", "between", "both", "but", "by", "can't", "cannot",
    "could", "couldn't", "did", "didn't", "do", "does", "doesn't", "doing", "don't",
    "down", "each", "few", "for", "from", "further", "had", "hadn't", "has", "hasn't",
    "have", "haven't", "having", "he", "he'd", "he'll", "he's", "her", "here", "here's",
    "hers", "herself", "him", "himself", "his", "how's", "i", "i'd", "i'll", "i'm", "i've",
    "if", "in", "into", "is", "isn't", "it", "it's", "its", "itself", "let's", "me", "more",
    "most", "mustn't", "my", "myself", "no", "nor", "not", "of", "off", "on", "once", "only",
    "or", "other", "ought", "our", "ours", "ourselves", "out", "over", "own", "same", "shan't",
    "she", "she'd", "she'll", "she's", "should", "shouldn't", "so", "some", "such", "through", "to",
    "there", "there's", "these", "they", "they'd", "they'll", "they're", "they've", "this", "those",
    "we", "we'd", "we'll", "we're", "we've", "were", "weren't", "what", "what's", "when", "when's",
    "where", "where's", "which", "too", "up", "while", "who", "who's", "whom", "why", "why's", "with",
    "won't", "would", "wouldn't", "you", "you'd", "you'll", "you're", "was", "wasn't",
    "you've", "your", "yours", "yourself", "yourselves", "than", "that", "that's", "the", "their",
    "theirs", "them", "themselves", "then");


    public static function checkInStop($word){
        $word = strtolower(trim($word));
        foreach(self::words as $str){
            if(strcmp($word, $str) == 0) return true;
        }
        return false;
    }

    public static function removeStop($dict){
        $arr = [];
        foreach($dict as $key => $value) {
            if(!self::checkInStop($key)){
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    public static function getStop(){
        return self::words;
    }
}