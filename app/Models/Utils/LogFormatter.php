<?php

namespace App\Models\Utils;


class LogFormatter 
{
    public static function formatTextLog($messages)
    {
        $delimitator = "##################################################################################################################";

        $row = "------------------------------------------------------------------------------------------------------------------";

        $newLine = "\n";

        $logText = "" . $newLine;


        $logText .= $delimitator . $newLine;

        foreach ($messages as  $message) {
            if (isset($message)) {

                $logText .= $message . $newLine;

                $logText .= $row . $newLine;
            }
        }

        $logText .= $delimitator . $newLine;

        return $logText;
    }
}
