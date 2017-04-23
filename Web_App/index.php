<?php
    if( $_SERVER['REQUEST_METHOD']=='GET' && isset( $_GET['ajax'] ) ){

        $dbhost =   'mysql.hostinger.in';
        $dbuser =   'u417378139_test'; 
        $dbpwd  =   'qwerty'; 
        $dbname =   'u417378139_test';
        $db     =   new mysqli( $dbhost, $dbuser, $dbpwd, $dbname );

        $places=array();

        $sql    =   'select 
                        `location_name` as \'name\',
                        `location_Latitude` as \'lat\',
                        `location_Longitude` as \'lng\',
                        `level` as \'des\'
                        from `maps`
                        limit 100';

        $res    =   $db->query( $sql );
        if( $res ) 
	while( $rs=$res->fetch_object() ) 
	$places[]=array( 'latitude'=>$rs->lat, 'longitude'=>$rs->lng, 'name'=>$rs->name ,'desc'=>$rs->des);
        $db->close();

        header( 'Content-Type: application/json' );
        echo json_encode( $places,JSON_FORCE_OBJECT );
        exit();
    }
?>
<!doctype html>
<html>
    <head>    
<meta name="description" content="Get the Delhi Pollution forecast. Access hourly, forecasts along with up to the minute reports and videos for Delhi, India from KCT Students Project" />

        <title>Delhi Pollution Data Prediction Project using Google Maps</title>
	 <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.indigo-pink.min.css">	
	      <script src="https://storage.googleapis.com/code.getmdl.io/1.0.6/material.min.js"></script>
	      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src='https://maps.google.com/maps/api/js' type='text/javascript'></script>
        <script type='text/javascript'>
            (function(){

                var map,marker,latlng,bounds,infowin;
                /* initial locations for map */
                var _lat=28.6814;
                var _lng=77.2227;

                var getacara=0; /* What should this be? is it a function, a variable ???*/

                function showMap(){
                    /* set the default initial location */
                    latlng={ lat: _lat, lng: _lng };

                    bounds = new google.maps.LatLngBounds();
                    infowin = new google.maps.InfoWindow();

                    /* invoke the map */
                    map = new google.maps.Map( document.getElementById('map'), {
                       center:latlng,
                       zoom: 10
                    });

                    /* show the initial marker */
                    marker = new google.maps.Marker({
                       position:latlng,
                       map: map,
                       title: 'Hello World!'
                    });
                    bounds.extend( marker.position );


                    /* I think you can use the jQuery like this within the showMap function? */
                    $.ajax({
                        /* 
                            I'm using the same page for the db results but you would 
                            change the below to point to your php script ~ namely
                            phpmobile/getlanglong.php
                        */
                        url: document.location.href,/*'phpmobile/getlanglong.php'*/
                        data: { 'id': getacara, 'ajax':true },
                        dataType: 'json',
                        success: function( data, status ){
                            $.each( data, function( i,item ){
                                /* add a marker for each location in response data */ 
                                addMarker( item.latitude, item.longitude, item.name,item.desc );
                            });
                        },
                        error: function(){
                            output.text('There was an error loading the data.');
                        }
                    });                 
                }

                /* simple function just to add a new marker */
                function addMarker(lat,lng,title,desc){
                    marker = new google.maps.Marker({/* Cast the returned data as floats using parseFloat() */
                       position:{ lat:parseFloat( lat ), lng:parseFloat( lng ) },
                       map:map,
                       title:'<b>'+title+'</b>'+'<br/>'+'Pollution Level:  '+desc
                    });

                    google.maps.event.addListener( marker, 'click', function(event){
                        infowin.setContent(this.title);
                        infowin.open(map,this);
                        infowin.setPosition(this.position);
                    }.bind( marker ));

                    bounds.extend( marker.position );
                    map.fitBounds( bounds );
                }


                document.addEventListener( 'DOMContentLoaded', showMap, false );
            }());
	 function trigger_notification()
            {
                //check if browser supports notification API
                if("Notification" in window)
                {
                    if(Notification.permission == "granted")
                    {
                        var notification = new Notification("Pollution Project", {"body":"Now pollutants in the air which are updated now , check your surrounding pollution level to keep your health ", "icon":"http://qnimate.com/wp-content/uploads/2014/07/web-notification-api-300x150.jpg"});
                    }
                    else
                    {
                        Notification.requestPermission(function (permission) {
                            if (permission === "granted") 
                            {
                                var notification = new Notification("Pollution Notification", {"body":"Now pollutants in the air which are updated now , check your surrounding pollution level to keep your health", "icon":"http://qnimate.com/wp-content/uploads/2014/07/web-notification-api-300x150.jpg"});
                            }
                        });
                    }
                }   
                else
                {
                    alert("Your browser doesn't support notfication API");
                }       
            }

	var now = new Date();
	var delay =  60*1000; // 1 hour in msec
	var start = delay - (now.getMinutes() * 60 + now.getSeconds()) * 1000 + now.getMilliseconds();

	setTimeout(function doSomething() {
   // do the operation
   // ... your code here...
 	trigger_notification();
   // schedule the next tick
   	setTimeout(doSomething, delay);
	}, start);
        </script>
        <style>
            html, html body, #map{ height:400px; width:100%; padding:0; margin:2; }
            .footer {
  		
  		background-color: #efefef;
  		text-align: center;
		}
.container {
  margin: 10px auto;
  width: 1000px;
}
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}
ul.a {list-style-type: circle;}

        </style>
	
    </head>
    <body onload="doSomething()">

   <div class="mdl-layout__header">
         <div class="mdl-layout__header-row">      
            <span class="mdl-layout-title">Delhi Pollution prediction Project </span>          
         </div>       
      </div>     
   
	<center>
	<h1></h1></center>

        <div id='map'></div>
		<div class="container">
		<h2>Pollution Levels </h2>	
  		<ul>
		<li>Good</li>
		<li>Satisfactory</li>	
		<li>Poor or unhealthy</li>	
		<li>Very poor or very unhealthy</li>
		<li>Severe</li>	
		</ul>
		
<!--div class="footer"> © 2017 This is done by Kct Student Project</div-->
<center>
<h2>Pollution Data </h2>
<?php
	$dbhost =   'mysql.hostinger.in';
        $dbuser =   'u417378139_test'; 
        $dbpwd  =   'qwerty'; 
        $dbname =   'u417378139_test';
        $db     =   new mysqli( $dbhost, $dbuser, $dbpwd, $dbname );
		$conn = mysqli_connect($dbhost, $dbuser, $dbpwd, $dbname);
		$sql="truncate lastdata;";
		mysqli_query($conn,$sql	);
		
		 $result =$conn->query("SELECT MAX(id) `max` FROM table3");      
        if (!$result) die($conn->error);
        while($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
        {
			$row;
            $temp=$row['max'];
			#echo $temp;
        } 
		$sql="select * from table3 where id='$temp'";
$result=mysqli_query($conn,$sql	)or die("Failed to connect to the database" .mysqli_error($conn));
$column=mysqli_fetch_assoc($result);

	$l="Civil Lines";
	$d1=$column['date'];	
	$d2=$column['COL4'];
	$d3=$column['COL5'];
	$d4=$column['COL6'];
	$d5=$column['COL7'];
	$d6=$column['COL8'];
	$sql="insert into lastdata(location_names,Date,o3,so2,co,no2,Level)values('$l','$d1','$d2','$d3','$d4','$d5','$d6');";
	$success=mysqli_query($conn,$sql);
	$sql="UPDATE maps SET level = '$d6' WHERE location_name = '$l';";	
	$success=mysqli_query($conn,$sql);
	if($success)
	{
		#echo "civil lines done";		
	}
		$l="Punjabi Bagh";
	$d1=$column['date'];	
	$d2=$column['COL10'];
	$d3=$column['COL11'];
	$d4=$column['COL12'];
	$d5=$column['COL13'];
	$d6=$column['COL14'];
	$sql="insert into lastdata(location_names,Date,o3,so2,co,no2,Level)values('$l','$d1','$d2','$d3','$d4','$d5','$d6');";
	$success=mysqli_query($conn,$sql);
	$sql="UPDATE maps SET level = '$d6' WHERE location_name = '$l';";	
	$success=mysqli_query($conn,$sql);
	if($success)

	{
		#echo "PB done";		
	}
		$l="R K Puram";
	$d1=$column['date'];	
	$d2=$column['COL16'];
	$d3=$column['COL17'];
	$d4=$column['COL18'];
	$d5=$column['COL19'];
	$d6=$column['COL20'];
	$sql="insert into lastdata(location_names,Date,o3,so2,co,no2,Level)values('$l','$d1','$d2','$d3','$d4','$d5','$d6');";
	$success=mysqli_query($conn,$sql);
	$sql="UPDATE maps SET level = '$d6' WHERE location_name = '$l';";	
	$success=mysqli_query($conn,$sql);
	if($success)
	{
		#echo "r k puram done";		
	}	
		$l="Mandir_marg";
	$d1=$column['date'];	
	$d2=$column['COL22'];
	$d3=$column['COL23'];
	$d4=$column['COL24'];
	$d5=$column['COL25'];
	$d6=$column['COL26'];
	$sql="insert into lastdata(location_names,Date,o3,so2,co,no2,Level)values('$l','$d1','$d2','$d3','$d4','$d5','$d6');";
	$success=mysqli_query($conn,$sql);
	$sql="UPDATE maps SET level = '$d6' WHERE location_name = '$l';";	
	$success=mysqli_query($conn,$sql);
	if($success)
	{
		#echo "m marg done";		
	}
	$l="Indira Gandhi International Airport";
	$d1=$column['date'];	
	$d2=$column['COL28'];
	$d3=$column['COL29'];
	$d4=$column['COL30'];
	$d5=$column['COL31'];
	$d6=$column['COL32'];
	$sql="insert into lastdata(location_names,Date,o3,so2,co,no2,Level)values('$l','$d1','$d2','$d3','$d4','$d5','$d6');";
	$success=mysqli_query($conn,$sql);
	$sql="UPDATE maps SET level = '$d6' WHERE location_name = '$l';";	
	$success=mysqli_query($conn,$sql);
	if($success)
	{
		#echo "airport done";		
	}
?>
<?php

/**
* This example describes the multi seriese chart preparation using FusionCharts PHP wrapper
*/


// Including the wrapper file in the page
	$dbhost =   'mysql.hostinger.in';
        $dbuser =   'u417378139_test'; 
        $dbpwd  =   'qwerty'; 
        $dbname =   'u417378139_test';
       $dbhandle     =   new mysqli( $dbhost, $dbuser, $dbpwd, $dbname );

	     	// Form the SQL query that returns the top 10 most populous countrie
	    	$strQuery = "SELECT location_names ,So2,No2,Co,O3,Level FROM lastdata";	
		$result = $dbhandle->query($strQuery) or exit("Error code ({$dbhandle->errno}): {$dbhandle->error}");
print '<table border="1"><tr>';
 while ($fieldinfo=mysqli_fetch_field($result))
          {
          echo "<th>". $fieldinfo->name . "</th>";
     	}
echo '</tr>';
while($row = $result->fetch_assoc()) {
    print '<tr>';
    print '<td>'.$row["location_names"].'</td>';
    print '<td>'.$row["So2"].'</td>';
    print '<td>'.$row["No2"].'</td>';
    print '<td>'.$row["Co"].'</td>';
    print '<td>'.$row["O3"].'</td>';
    print '<td>'.$row["Level"].'</td>';
    print '</tr>';
}  
print '</table>';
?>
<h2>Health Hazards </h2>
</center>
<img src="health.png">


</div>
 
</body>
 <div class="footer">
                © 2017 students project: <a href="http://gowthamp.16mb.com/"> All Rights </a>
 </div>
</html>
