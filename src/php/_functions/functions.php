<?php

    // VP - Funktion zum schreiben von Log Dateien
    function writeLog($str, $path) {
        $logfile = fopen($path, "a");
        fputs($logfile, "\r\n" . date("d.m.Y H:i:s", time()) . " Uhr | " . $str);
        fclose($logfile);
    }

    // VP - Funktion zum schreiben von Log Dateien
    function clearString($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '-', $string); // Removes special chars.
	}