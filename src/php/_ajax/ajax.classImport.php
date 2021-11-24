<?php
    require_once '../_class/Core/Container.php';
	require_once '../_class/User/UserRepository.php';
    require_once '../_class/Classes/ClassesRepository.php';
    require_once '../_class/Classes/ClassesController.php';
    require_once '../_class/Classes/ClassesModel.php';
    require_once '../_functions/functions.php';

    $container = new Core\Container();
    $classController = $container->make("classesController");
    
    // Hochgeladene Datei in Variable speichern (GR)
	$upload_url = "../../../dist/import/classes/";
	if (!is_dir($upload_url)) {
		mkdir($upload_url);
	}
	$logPath = "../../../dist/import/logs/classImport.log";

	if (file_exists($logPath)) {
		fclose(fopen($logPath, 'w'));
	}

	// Array von erlaubten Dateiformaten (GR)
	$allowed = array('csv');
	$filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
	
	$failCount = 0;
	$successCount = 0;
	$typeError = false;
	$correctFormat = true;

    // Prüfung auf erlaubtes Dateiformat (GR)
	if (in_array($extension, $allowed)) {

		// Vergabe eines einzigartigen Dateinamens mittels UNIX-Zeitstempel (GR)
		$uniqueFileName = time() . '.' . $extension;
		$target_path = $upload_url . $uniqueFileName; // Zielpfad
		
		
		if ((move_uploaded_file($_FILES['file']['tmp_name'], $target_path))) {

			// Datei öfnnen
			$handle = fopen($target_path, "r");
			$counter = 1; // Prüfung der ersten Zeile auf gültigen Header

			// Scan jeder Zeile mit dem Trennzeichen ";"
			while (($fData = fgetcsv($handle, 1000, ";")) !== FALSE) {

				// Speicherung der Werte innerhalb der Variablen
				if (isset($fData[0]) && isset($fData[1])) {
					$data = new stdClass;
					$data -> name = trim($fData[0]);
					$data -> password = $fData[1];
				} else {
					writeLog("Zeile: " . $counter. " inkorrektes Format", $logPath);
					$correctFormat = false;
					break;
				}
				
				// Überprüfung auf richtige Spaltennamen (Name;Passwort)
				if ($counter == 1 && (strtolower($data -> name) != 'name' || strtolower($data -> password) != 'password')) {
					// Falsches Format, Abbruch der Schleife. Ausgabe des Fehlers in die Log-Datei
					writeLog("Kopfzeile hat inkorrektes Format", $logPath);
					$correctFormat = false;
					break;
				} else {
					// Prüfing auf ungültige Headerzeile
					if ($counter > 1) {
						$duplicate = false;
						$importOk = false;
						if (strlen($data -> password) >= 8) {
							$importOk = $classController->queryClass($data, "import", $duplicate);	
						}
						
                        if($importOk){
                            writeLog("Die Klasse: " . $data->name . " wurde erfolgreich importiert", $logPath);
							$successCount ++;    					    
                        } else {
							if($duplicate) {
								writeLog("Die Klasse: ".$data -> name. " existiert bereits." 
                            	, $logPath);
							} else if (strlen($data -> password) < 8) {
								writeLog("Das Passwort der Klasse: " . $data->name . " ist zu kurz!", $logPath);
							} else {
								writeLog("Die Klasse: ".$data -> name." konnte nicht importiert werden." 
                            	, $logPath);
							}
							$failCount ++;
                        }
                    }
				}
				$counter++;

			}

		}

	} else {
		// Ungültiges Dateiformat
		$typeError = true;
	}
	$obj = new stdClass;
    if(!$typeError && $correctFormat) {
		$obj->status = "success";
		$obj->successCount = $successCount;
		$obj->failCount = $failCount;
	} else {
		// Ausgabe der Fehlermeldung
		if (!$correctFormat) {
			$obj->status = "wrong_format";
		} else {
			$obj->status = "type_error";
		}	
	}

	//Nach dem Import, Datei wieder löschen (GR)
	$importFiles = glob("../../../dist/import/classes/*");
	foreach ($importFiles as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}

    $rtn = json_encode($obj);
    echo $rtn;
