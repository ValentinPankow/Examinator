<?php
    require_once '../_class/Core/Container.php';
    require_once '../_class/User/UserRepository.php';
    require_once '../_class/User/UserController.php';
    require_once '../_class/User/UserModel.php';
    require_once '../_functions/functions.php';

    $container = new Core\Container();
    $userController = $container->make("userController");
    
    // Store the uploaded File in variables ---- Change $upload_url to the Folder where the file should be stored
	$upload_url = "../../../dist/import/";
	// Array of allowed data types
	$allowed = array('csv');
	$filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
	
	$failCount = 0;
	$sucssesCount = 0;
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
				$data -> firstname = $fData[0];
				$data -> lastname = $fData[1];
				$data -> email = $fData[2];
				$data -> password = $fData[3];
				$data -> isAdmin = $fData[4];
				$data -> isTeacher = $fData[5];
				

				// Check for correct headings to ensure correct format. ------ In this example the format of the csv headers has to be: is,correct,format
				if ($counter == 1 && ($data -> firstname != 'firstname' || $data -> lastname != 'lastname' || $data -> email != 'email' ||
                $data -> password != 'password' ||$data -> session_id != 'session_id' || $data -> isAdmin != 'isAdmin' || $data -> isTeacher != 'isTeacher')) {
					// Not the correct format -> break the loop to not go further
					writeLog("Kopfzeile hat inkorrektes Format", "../../../dist/import/logs/userImport.log");
                    break;
				} else {
					// If not header row
					if ($counter > 1) {
						$duplicate = fales;
                        $importOk = $userController->queryUser($data,"insert", $duplicate);
                        if($importOk){
                            writeLog("Import war erfolgreich", "../../../dist/import/logs/userImport.log");
							$sucssesCount ++;    					    
                        } else {
							if($duplicate){
								writeLog("Der Benutzer: ".$data -> firstname." ".$data -> lastname." mit der E-Mail Adresse: ". $data -> email . " existiert bereits." 
                            	, "../../../dist/import/logs/userImport.log");
							}else{
								writeLog("Der Benutzer: ".$data -> firstname." ".$data -> lastname." mit der E-Mail Adresse: ". $data -> email . " konnte nicht importiert werden." 
                            	, "../../../dist/import/logs/userImport.log");
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
		$obj->successCount = $sucssesCount;
		$obj->failCount = $failCount;
	}else{
		$obj->status = "type_error";	
	}
    $rtn = json_encode($obj);
    echo $rtn;
