<?php
    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';
    require_once '../_functions/functions.php';

    $container = new Core\Container();
    $userController = $container->make("userController");

	// Store the uploaded File in variables ---- Change $upload_url to the Folder where the file should be stored
	$upload_url = "../../../dist/import/users/";
	if (!is_dir($upload_url)) {
		mkdir($upload_url);
	}
	$logPath = "../../../dist/import/logs/userImport.log";

	if (file_exists($logPath)) {
		fclose(fopen($logPath, 'w'));
	}

	// Array of allowed data types
	$allowed = array('csv');
	$filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
	
	$failCount = 0;
	$sucssesCount = 0;
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
				if (isset($fData[0]) && isset($fData[1]) && isset($fData[2]) && isset($fData[3]) && isset($fData[4]) && isset($fData[5])) {
					$data = new stdClass;
					$data -> firstname = trim($fData[0]);
					$data -> lastname = trim($fData[1]);
					$data -> email = trim($fData[2]);
					$data -> password = $fData[3];
					$data -> isAdmin = trim($fData[4]);
					$data -> isTeacher = trim($fData[5]);
				} else {
					writeLog("Zeile: " . $counter. " inkorrektes Format", $logPath);
					$correctFormat = false;
					break;
				}

				// Check for correct headings to ensure correct format. ------ In this example the format of the csv headers has to be: is,correct,format
				if ($counter == 1 && (strtolower($data -> firstname) != 'firstname' || strtolower($data -> lastname) != 'lastname' || strtolower($data -> email) != 'email' ||
                strtolower($data -> password) != 'password' || strtolower($data -> isAdmin) != 'isadmin' || strtolower($data -> isTeacher) != 'isteacher')) {
					// Not the correct format -> break the loop to not go further
					writeLog("Kopfzeile hat inkorrektes Format", $logPath);
                    $correctFormat = false;
					break;
				} else {
					// If not header row
					if ($counter > 1) {
						$duplicate = false;
						$importOk = false;

						if (strlen($data -> password) >= 8 && filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                        	$importOk = $userController->queryUser($data,"insert", $duplicate);
						}

						if($importOk){
                            writeLog("Import des Benutzers: ".$data->firstname . " ". $data->lastname. " ".$data->email ." war erfolgreich", $logPath);
							$sucssesCount ++;    					    
                        } else {
							if($duplicate){
								writeLog("Der Benutzer: ".$data -> firstname." ".$data -> lastname." mit der E-Mail Adresse: ". $data -> email . " existiert bereits." 
                            	, $logPath);
							} else if (strlen($data -> password) < 8) {
								writeLog("Das Passwort des benutzers: " . $data->firstname. " ".$data->lastname." ".$data->email." ist zu kurz!", $logPath);
							} else if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
								writeLog("Die E-Mail des benutzers: " . $data->firstname. " ".$data->lastname." ".$data->email." ist nicht valide!", $logPath);
							} else {
								writeLog("Der Benutzer: ".$data -> firstname." ".$data -> lastname." mit der E-Mail Adresse: ". $data -> email . " konnte nicht importiert werden." 
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
		$obj->successCount = $sucssesCount;
		$obj->failCount = $failCount;
	}else{
		if (!$correctFormat) {
			$obj->status = "wrong_format";
		} else {
			$obj->status = "type_error";
		}	
	}

	$importFiles = glob("../../../dist/import/users/*");
	foreach ($importFiles as $file) {
		if (is_file($file)) {
			unlink($file);
		}
	}

    $rtn = json_encode($obj);
    echo $rtn;
