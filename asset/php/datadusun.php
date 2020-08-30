<?php
	$dataSpreadsheetUrl = "https://docs.google.com/spreadsheets/d/e/2PACX-1vRqKWyEH4Q5HhQfXqLIa63qXMzsWX1laRzd6MLPwTKK0nxxtmv2w0SLujwa90aM0QdfdFgXSXt-1BkC/pub?gid=1493815942&single=true&output=csv";

  $rowCount = 0;
  $features = array();
  $error = FALSE;
  $output = array();

  // attempt to set the socket timeout, if it fails then echo an error
  if ( ! ini_set('default_socket_timeout', 15))
  {
    $output = array('error' => 'Unable to Change PHP Socket Timeout');
    $error = TRUE;
  } // end if, set socket timeout

  // if the opening the CSV file handler does not fail
  if ( !$error && (($dataHandle = fopen($dataSpreadsheetUrl, "r")) !== FALSE) )
  {
    // while CSV has data, read up to 10000 rows
    while (($csvRow = fgetcsv($dataHandle, 10000, ",")) !== FALSE)
    {
      $rowCount++;
      if ($rowCount == 1) { continue; } // skip the first/header row of the CSV

      $output[] = array(
        'type' => 'Feature',
        'properties' => array(
          'rumah' => $csvRow[2],
          'RT' => $csvRow[3],
          'kode' => $csvRow[4],
        ),
        'geometry' => array(
          'type' => 'Point',
          'coordinates' => array(
            $csvRow[0],$csvRow[1],'0.0'
          )
        )

      );
    } // end while, loop through CSV data

    fclose($dataHandle); // close the CSV file handler
    
  }  // end if , read file handler opened

  // else, file didn't open for reading
  else
  {
    $output = array('error' => 'Problem Reading Google CSV');
  }  // end else, file open fail


  $output_new = array(
    'type' => 'FeatureCollection',
    'name' => 'Data_Dusun',
    'crs' => [
      'type'=> 'name',
      'properties' => [
        'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'
      ]],
    'features' => $output
  );

  $json_datatitikdusun = json_encode($output_new, JSON_NUMERIC_CHECK);

  // echo $json_datatitikdusun;

?>