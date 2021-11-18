<?php

	// Store the uploaded File in variables ---- Change $upload_url to the Folder where the file should be stored
	$upload_url = "/dist/import/classes/";
	// Array of allowed data types
	$allowed = array('csv');
	$filename = $_FILES['file']['name'];
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
	
	// If files data type (extension) is allowed
	if (in_array($extension, $allowed)) {

		// Give the file a unique name and remove special chars and spaces to avoid security risks
		$uniqueFileName = time() . '.' . $extension; // Create unique file name using UNIX Timestamp
		// $newFileName = clearFileName(basename($_FILES['file']['name'])); // Or create new file name removing special chars and spaces but keep original name
		$target_path = $upload_url . $uniqueFileName; // Destination path
		// $target_path = $upload_url . $newFileName; // Destination path

		// Move the file to the upload directory
		if ((move_uploaded_file($_FILES['file']['tmp_name'], $target_path))) {

			// Open the file
			$handle = fopen($target_path, "r");
			$counter = 1; // to check if first row in csv is a header row

			// Go throu each row and seperate the values by ','
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

				// Define the variables for the columns of csv and store the values in them
				$your_item = $data[0];
				$your_item2 = $data[1];
				$your_item3 = $data[2];

				// Check for correct headings to ensure correct format. ------ In this example the format of the csv headers has to be: is,correct,format
				if ($counter == 1 && ($your_item != 'is' || $your_item2 != 'correct' || $your_item3 != 'format')) {
					// Not the correct format -> break the loop to not go further
					break;
				} else {
					// If not header row
					if ($counter > 1) {

						// Do own logic here
						

					}
				}
				
				// Set counter +1 for new row
				$counter++;

			}

		}

	} else {
		
		// File has not the correct data type (extension)
		
	}

	function clearFileName($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '-', $string); // Removes special chars.
	}
