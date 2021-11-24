<?php

    // VP

    require_once("../db_config.php");
    require_once '../_class/Core/Container.php';
    require_once '../_class/Subjects/SubjectsRepository.php';
    require_once '../_class/Subjects/SubjectManagement/SubjectManagementController.php';
    require_once '../_class/Subjects/SubjectsModel.php';
    require_once '../_functions/functions.php';

    $container = new Core\Container();
    $subjectManagementController = $container->make("subjectmanagementController");

    // Store the uploaded File in variables ---- Change $upload_url to the Folder where the file should be stored
    $upload_url = "../../../dist/import/subjects/";
    if (!is_dir($upload_url)) {
		mkdir($upload_url);
	}
    $logPath = "../../../dist/import/logs/subjectsImport.log";

    if (file_exists($logPath)) {
        fclose(fopen($logPath, 'w'));
    }

    // Array of allowed data types
    $allowed = array('csv');
    $filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $failCount = 0;
    $successCount = 0;
    $typeError = false;
    $correctFormat = true;

    // If files data type (extension) is allowed
    if (in_array($extension, $allowed)) {

        // Give the file a unique name and remove special chars and spaces to avoid security risks
        $uniqueFileName = time() . '.' . $extension; // Create unique file name using UNIX Timestamp
        $target_path = $upload_url . $uniqueFileName; // Destination path
        
        // Move the file to the upload directory
        if ((move_uploaded_file($_FILES['file']['tmp_name'], $target_path))) {

            // Open the file
            $handle = fopen($target_path, "r");
            $counter = 1; // to check if first row in csv is a header row

            // Go throu each row and seperate the values by ';'
            while (($fData = fgetcsv($handle, 1000, ";")) !== FALSE) {

                // Define the variables for the columns of csv and store the values in them
                if (isset($fData[0])) {
                    $data = new stdClass;
                    $data -> name = trim($fData[0]);
                } else {
                    writeLog("Zeile: " . $counter. " inkorrektes Format", $logPath);
                    $correctFormat = false;
                    break;
                }
                
                // Überprüfung auf richtige Spaltennamen (Name;)
                if ($counter == 1 && (strtolower($data -> name) != 'name')) {
                    // Falsches Format, Abbruch der Schleife. Ausgabe des Fehlers in die Log-Datei
                    writeLog("Kopfzeile hat inkorrektes Format", $logPath);
                    $correctFormat = false;
                    break;
                } else {
                    // If not header row
                    if ($counter > 1) {
                        $duplicate = false;
                        $importOk = $subjectManagementController->querySubject($data, "insert", $duplicate);	
                        
                        if($importOk){
                            writeLog("Das Fach: " . $data->name . " wurde erfolgreich importiert", $logPath);
                            $successCount ++;    					    
                        } else {
                            if($duplicate) {
                                writeLog("Das Fach: ".$data -> name. " existiert bereits." 
                                , $logPath);
                            } else {
                                writeLog("Das Fach: ".$data -> name." konnte nicht importiert werden." 
                                , $logPath);
                            }
                            $failCount ++;
                        }
                    }
                }
                
                // Set counter +1 for new row
                $counter++;

            }

        }

    } else {
        // File has not the correct data type (extension)
        $typeError = true;
    }
    $obj = new stdClass;
    if(!$typeError && $correctFormat){
        $obj->status = "success";
        $obj->successCount = $successCount;
        $obj->failCount = $failCount;
    }else{
        if (!$correctFormat) {
            $obj->status = "wrong_format";
        } else {
            $obj->status = "type_error";
        }	
    }

    $importFiles = glob("../../../dist/import/subjects/*");
    foreach ($importFiles as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    $rtn = json_encode($obj);
    echo $rtn;
