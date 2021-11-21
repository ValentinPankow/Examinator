<?php
    require_once '../_class/Core/Container.php';
	require_once '../_class/User/UserRepository.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_functions/functions.php';

    $container = new Core\Container();
    $classController = $container->make("classesController");
    
    // Store the uploaded File in variables ---- Change $upload_url to the Folder where the file should be stored
	$upload_url = "../../../dist/import/classes/";
	$importFiles = glob("../../../dist/import/classes/*");

	foreach ($importFiles as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}
	// Array of allowed data types
	$allowed = array('csv');
	$filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
	
	$failCount = 0;
	$successCount = 0;
	$typeError = false;

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
				$data = new stdClass;
				$data -> name = trim($fData[0]);
				$data -> password = $fData[1];
				

				// Überprüfung auf richtige Spaltennamen (Name;Passwort)
				if ($counter == 1 && ($data -> name != 'name' || $data -> password != 'password')) {
					// Falsches Format, Abbruch der Schleife. Ausgabe des Fehlers in die Log-Datei
					writeLog("Kopfzeile hat inkorrektes Format", "../../../dist/import/logs/classImport.log");
                    break;
				} else {
					// If not header row
					if ($counter > 1) {
						$duplicate = false;
						$importOk = false;
						if (strlen($data -> password) >= 8) {
							$importOk = $classController->queryClass($data, "insert", $duplicate);	
						}
						
                        if($importOk){
                            writeLog("Die Klasse: " . $data->name . " wurde erfolgreich importiert", "../../../dist/import/logs/classImport.log");
							$successCount ++;    					    
                        } else {
							if($duplicate) {
								writeLog("Die Klasse: ".$data -> name. " existiert bereits." 
                            	, "../../../dist/import/logs/classImport.log");
							} else if (strlen($data -> password) < 8) {
								writeLog("Das Passwort der Klasse: " . $data->name . " ist zu kurz!", "../../../dist/import/logs/classImport.log");
							} else {
								writeLog("Die Klasse: ".$data -> name." konnte nicht importiert werden." 
                            	, "../../../dist/import/logs/classImport.log");
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
    if(!$typeError){
		$obj->status = "success";
		$obj->successCount = $successCount;
		$obj->failCount = $failCount;
	}else{
		$obj->status = "type_error";	
	}
    $rtn = json_encode($obj);
    echo $rtn;
