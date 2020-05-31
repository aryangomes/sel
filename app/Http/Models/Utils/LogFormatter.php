<?php

namespace App\Http\Models\Utils;

use Illuminate\Database\Eloquent\Model;

class LogFormatter extends Model
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
