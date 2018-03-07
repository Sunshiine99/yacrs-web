<?php

class DatabaseAnalysis
{

    /**
     * @param int $sessionQuestionID
     * @param mysqli $mysqli
     * @return array|null
     */
    public static function load($sessionQuestionID, $mysqli) {
        $sessionQuestionID = Database::safe($sessionQuestionID, $mysqli);

        $sql = "SELECT a.*, r.`response`
                FROM
                    `yacrs_analysis` AS a,
                    `yacrs_response` AS r
                WHERE a.`responseID` = r.`ID`
                  AND r.`sessionQuestionID` = $sessionQuestionID
                ORDER BY a.`cluster` ASC";
        $result = $mysqli->query($sql);

        if(!$result) return null;

        $output = [];

        while($row = $result->fetch_assoc()) {
            $item = [];
            $item["x"] = $row["x"];
            $item["y"] = $row["y"];
            $item["response"] = $row["response"];
            $output[intval($row["cluster"])][] = $item;
        }

        return $output;
    }
}