<?php
 
/*
 * Following code will list all the marker
 */
 
// array for JSON response
$response = array();
	$dbhost =   'localhost';
        $dbuser =   'root'; 
        $dbpwd  =   ''; 
        $dbname =   'pollution';
        $db     =   new mysqli( $dbhost, $dbuser, $dbpwd, $dbname );

       	$sql    =   'select 
                        `location_name` as \'name\',
                        `location_Latitude` as \'lat\',
                        `location_Longitude` as \'lng\',
                        `level` as \'des\'
                        from `maps`
                        limit 100';
// get all marker
        $result    =   $db->query( $sql );
 
// check for empty result
    $response["markers"] = array();
 
    while( $row=$result->fetch_object() )  {
        // temp user array
        $marker = array();
        $marker["location_name"] = $row->name;
        $marker["Latitude"] = $row->lat;
        $marker["Longitude"] = $row->lng;
        $marker["PollutionLevel"] = $row->des;
        
 
        // push single product into final response array
        array_push($response["markers"], $marker);
    }
 
    echo json_encode($response);
?>
