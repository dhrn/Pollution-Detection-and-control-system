package com.example.dhrn.mapsprojectapp;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.model.BitmapDescriptor;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public class MainActivity extends AppCompatActivity implements OnMapReadyCallback {

    private GoogleMap mMap;
    private Context context;
    private static String url = "http://192.168.0.110/demo/getall.php";

    JSONArray user = null;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Retrieve the content view that renders the map.
        setContentView(R.layout.activity_main);
        // Get the SupportMapFragment and request notification
        // when the map is ready to be used.



        SupportMapFragment mapFragment = (SupportMapFragment) getSupportFragmentManager()
                .findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);


    }
    @Override
    public void onMapReady(GoogleMap googleMap) {
        // Add a marker in Sydney, Australia,
        // and move the map's camera to the same location.
        mMap=googleMap;
    /*    LatLng rkpuram = new LatLng(28.5660, 77.1767);
        googleMap.addMarker(new MarkerOptions().position(rkpuram)
                .title(" Rkpuram")
                .snippet("Population: 4,137,400"));;
        googleMap.moveCamera(CameraUpdateFactory.newLatLng(rkpuram));
*/
       mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(new LatLng(22.9734,78.6569), 5));

    }
    @Override
    protected void onStart() {
        super.onStart();
        Toast.makeText(this,"OnStart or http requesting",Toast.LENGTH_SHORT).show();

        new JSONParse().execute();
    }



    private class JSONParse extends AsyncTask<String, String, JSONObject> {
        private ProgressDialog pDialog;

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            pDialog = new ProgressDialog(MainActivity.this);
            pDialog.setMessage("Getting Data ...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();

        }

        @Override
        protected JSONObject doInBackground(String... args) {
            JSONParser jParser = new JSONParser();

            // Getting JSON from URL
            JSONObject json = jParser.getJSONFromUrl(url);
            return json;
        }
        @Override
        protected void onPostExecute(JSONObject json) {
            pDialog.dismiss();
            try {
                // Getting JSON Array
                user = json.getJSONArray("markers");
                for (int i = 0; i < user.length(); i++){
                    JSONObject c = user.getJSONObject(i);

                    // Storing  JSON item in a Variable
                    String location_name = c.getString("location_name");
                    Float latitude = Float.parseFloat(c.getString("Latitude"));
                    Float longitude = Float.parseFloat(c.getString("Longitude"));
                    String pollution_level=c.getString("PollutionLevel");
                    Toast.makeText(MainActivity.this,"Get data"+location_name+latitude+longitude+pollution_level,Toast.LENGTH_SHORT).show();
                     mark_marker(latitude,longitude,location_name,pollution_level);
                }


            } catch (JSONException e) {
                e.printStackTrace();
            }

        }
    }

    private  void mark_marker(Float lat,Float lang,String location,String description){
        BitmapDescriptor icon = null;
        if(description.equals("Poor"))
         icon= BitmapDescriptorFactory.fromResource(R.drawable.poor);
        else if (description.equals("Satisfactory"))
            icon= BitmapDescriptorFactory.fromResource(R.drawable.statfis);
        else if (description.equals("Good"))
            icon= BitmapDescriptorFactory.fromResource(R.drawable.good);
        else if (description.equals("Moderate"))
            icon= BitmapDescriptorFactory.fromResource(R.drawable.moderate);



            LatLng locationinmap = new LatLng(lat, lang);
        mMap.addMarker(new MarkerOptions().position(locationinmap)
                .title(location)
                .snippet("Pollution Level:"+description)
                .icon(icon));;
        mMap.moveCamera(CameraUpdateFactory.newLatLng(locationinmap));
        Log.e("done","marker added");
    }


    @Override
    protected void onResume() {
        super.onResume();
        Toast.makeText(this,"OnResume",Toast.LENGTH_SHORT).show();
    }
    @Override
    protected void onPause() {
        super.onPause();
        Toast.makeText(this,"onPause",Toast.LENGTH_SHORT).show();
    }
    @Override
    protected void onStop() {
        super.onStop();
        Toast.makeText(this,"OnStop",Toast.LENGTH_SHORT).show();
    }

}



