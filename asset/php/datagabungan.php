<?php
	include 'datadusun.php';
  $dataSpreadsheetUrlpenghuni = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vRqKWyEH4Q5HhQfXqLIa63qXMzsWX1laRzd6MLPwTKK0nxxtmv2w0SLujwa90aM0QdfdFgXSXt-1BkC/pub?gid=797461358&single=true&output=csv';

  $rowCount = 0;
  $features = array();
  $error = FALSE;
  $datapenghuni = array();

  // attempt to set the socket timeout, if it fails then echo an error
  if ( ! ini_set('default_socket_timeout', 15))
  {
    $datapenghuni = array('error' => 'Unable to Change PHP Socket Timeout');
    $error = TRUE;
  } // end if, set socket timeout

  // if the opening the CSV file handler does not fail
  if ( !$error && (($dataHandle = fopen($dataSpreadsheetUrlpenghuni, "r")) !== FALSE) )
  {
    // while CSV has data, read up to 10000 rows
    while (($csvRow = fgetcsv($dataHandle, 10000, ",")) !== FALSE)
    {
      $rowCount++;
      if ($rowCount == 1) { continue; } // skip the first/header row of the CSV

      $datapenghuni[] = array(
        'features' => array(
          'kode'=> $csvRow[2],
          'kepala_keluarga'=> $csvRow[3],
          'keluarga'=> $csvRow[4],
          'jeniskelamin'=> $csvRow[5],
          'tgllhr'=> $csvRow[6],
          'status'=> $csvRow[7],
          

          // 'KODE' => $csvRow[0],
          // 'KECAMATAN' => $csvRow[1],
          // 'POSITIF' => $csvRow[2],
          // 'ODP' => $csvRow[3],
          // 'PDP' => $csvRow[4],
          // 'DIRAWAT' => $csvRow[5],
          // 'SEMBUH' => $csvRow[6],
          // 'MENINGGAL' => $csvRow[7],
        )
      );
    } // end while, loop through CSV data

    fclose($dataHandle); // close the CSV file handler
    
  }  // end if , read file handler opened

  // else, file didn't open for reading
  else
  {
    $datapenghuni = array('error' => 'Problem Reading Google CSV');
  }  // end else, file open fail

  //Read geojson file
  $polygonAdmin = json_decode($json_datatitikdusun, TRUE);

	foreach ($polygonAdmin['features'] as $key => $first_value) {
      foreach ($datapenghuni as $second_value) {
        if($first_value['properties']['kode']==$second_value['features']['kode']){
          $polygonAdmin['features'][$key]['properties']['anggota'][]=[
            "nama" => $second_value['features']['keluarga'],
            "status" => $second_value['features']['status'],
            "tgllhr" => $second_value['features']['tgllhr'],
            "jeniskelamin" => $second_value['features']['jeniskelamin'],

          ];

        } else {}
      } 
  }

	$combined_datapenghuni = json_encode($polygonAdmin, JSON_NUMERIC_CHECK); 

	// header("Access-Control-Allow-Origin: *");
  // // header('Cache-Control: no-cache, must-revalidate');
	// header('Content-Type: application/json');
	echo $combined_datapenghuni;
?>