<?php

/**
 * Takes a string and converts it to a boolean with special cases for certain strings
 * @param string $text
 * @return bool
 */
function text2bool($text) {

    // If a value that means true
    if(
        $text === true      ||
        $text === 1         ||
        $text === "1"       ||
        $text === "true"    ||
        $text === "t"
    ) return true;

    // If a value that means false
    if(
        $text === false     ||
        $text === 0         ||
        $text === "0"       ||
        $text === "false"   ||
        $text === "f"
    ) return false;

    // Otherwise use default boolval function to determine boolean value
    return boolval($text);
}

/**
 * Convert a bool to a string usable in a query
 * @param bool $bool
 * @return string
 */
function bool2dbString($bool) {
    return $bool ? "1" : "0";
}