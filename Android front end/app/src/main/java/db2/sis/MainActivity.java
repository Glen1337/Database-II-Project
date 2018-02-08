package db2.sis;

//import android.net.ConnectivityManager;
//import java.io.OutputStreamWriter;
//import java.net.URLConnection;
//import java.net.MalformedURLException;
//import android.net.NetworkInfo;
//import android.view.Menu;
//import java.io.InputStream;
//import java.io.BufferedInputStream;
//import android.view.inputmethod.InputMethodManager;
//import android.text.InputType;
//import java.io.DataOutputStream;
import android.os.AsyncTask;
import android.text.method.ScrollingMovementMethod;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.TextView;
import android.widget.EditText;
import android.widget.Button;
import android.view.View.OnClickListener;
import android.view.View;
import android.util.Log;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.net.URLEncoder;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.io.IOException;
import android.view.WindowManager;

public class MainActivity extends AppCompatActivity {

    //URL to php file
    public String myURL = "http://10.0.2.2/project/connector.php";

    //window components
    public TextView myResult;
    private EditText myInput;
    private Button myButton;
    private String idIN;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Don't display keyboard until editText is selected
        getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);

        //Create an instance of the Control object and capture it from the layout
        myResult = (TextView) findViewById(R.id.result);
        myInput = (EditText) findViewById(R.id.idID);
        myButton = (Button) findViewById(R.id.btnID);

        //make text window scrollable
        myResult.setMovementMethod(new ScrollingMovementMethod());

        // bind onClick callback function to click event
        myButton.setOnClickListener(new OnClickListener() {
            public void onClick(View v) {

                //idIN2 = Integer.parseInt(myInput.getText().toString());

                //get input from editText field
                idIN = myInput.getText().toString();
                myResult.setText(idIN);

                MyTask mytask = new MyTask();

                //Start Asynchronous task
                mytask.execute(idIN);

                myResult.setText("Input ID: " + idIN + "\n");
            }
        });

    }

    private class MyTask extends AsyncTask<String, Integer, String> {

        @Override
        protected void onPreExecute() {
            //super.onPreExecute();
            // Do something like display a progress bar
        }

        @Override
        protected String doInBackground(String... params) {

            String idIN = params[0];
            String myLink = "";
            URL url = null;
            HttpURLConnection urlConnection = null;

            try {

                //append student ID# to link
                myLink = myURL + "?id_in=" + URLEncoder.encode(idIN, "UTF-8");

            } catch (UnsupportedEncodingException uee) {
                uee.printStackTrace();
                Log.d("url encod", "urlencod");
            }
            Log.d("Link", myLink);

            try {
                //create URL object
                url = new URL(myLink);

            } catch (java.net.MalformedURLException mue) {
                Log.d("malformedURL", "malformedurl");
                mue.printStackTrace();
                return "malformedurlexception";
            }

            try {

                //use url to open HTTP connection
                urlConnection = (HttpURLConnection) url.openConnection();

            } catch (java.io.IOException ioe) {
                Log.d("ioe", "ioe");
                ioe.printStackTrace();
                return "ioeexception";
            }

            //prepare request
            urlConnection.setRequestProperty("Content-Type", "application/json");
            urlConnection.setReadTimeout(10000);
            urlConnection.setConnectTimeout(15000);
            try {

                urlConnection.setRequestMethod("GET");

            } catch (java.net.ProtocolException pe) {
                pe.printStackTrace();
                Log.d("pe", "pe");
                return "protocolexception";
            }

            urlConnection.setDoInput(true);
            urlConnection.setDoOutput(true);

            try {

                urlConnection.connect();

            } catch (IOException ioe) {
                ioe.printStackTrace();
                Log.d("url.connect", "no");
            }

            int response = -1;

            try {

                response = urlConnection.getResponseCode();

            } catch (java.io.IOException ioe) {

                ioe.printStackTrace();
                Log.d("ioe in response", "response from post");
                return "ioeexcpetion";
            }

            Log.d("Response code:", " "+response);

            BufferedReader input;
            String result;

            try {

                //set up buffered input reader
                input = new BufferedReader(new InputStreamReader(urlConnection.getInputStream()));

                // read response from GET request
                result = input.readLine();
                input.close();

            } catch (IOException ioe) {
                ioe.printStackTrace();
                Log.d("ioeexception", "ioeexception");
                return ("ioeexcpetion");
            }

            Log.d("response", result);

            urlConnection.disconnect();

            //return JSON string result to post execute function
            return result;
        }

        @Override
        protected void onPostExecute(String inString) {
            //super.onPostExecute();
            //myResult.append(inString);

            JSONObject jsonResult = null;
            String jsonCanGraduate = null;
            JSONArray reqsArray = null;
            String jsonStudent = null;

            try {
                jsonResult = new JSONObject(inString);
                jsonStudent = jsonResult.getString("student");

                if (jsonStudent.equals("invalid")){
                    Log.d("invalid student", "invalid student!!");
                    myResult.append("\nInvalid student\n");
                    return;
                }

                reqsArray = jsonResult.getJSONArray("reqs");
                jsonCanGraduate = jsonResult.getString("Can Graduate");

            } catch (JSONException je) {
                je.printStackTrace();
                Log.d("json exception", "json exception on gets");
            }

            //myResult.append(jsonResult.toString());
            myResult.append("\n");
            //myResult.append(reqsArray.toString());

            for (int i =0; i < reqsArray.length(); i++ ){

                try {
                    JSONObject currentObj = reqsArray.getJSONObject(i);

                    String reqName = currentObj.getString("Name");
                    String reqPass = currentObj.getString("Pass");
                    String reqReason = currentObj.getString("Reason");

                    myResult.append("Requirement: "+ reqName + "\n");
                    myResult.append("Fulfilled: "+ reqPass + "\n");
                    myResult.append("Reason: "+ reqReason + "\n");
                    myResult.append("\n");

                }catch(JSONException je){
                    je.printStackTrace();
                    Log.d("json excpetion", "in reqArray json parse");
                }
            }

            myResult.append("\n");
            myResult.append("Can you graduate: " + jsonCanGraduate + "\n\n");
            //myResult.append(jsonCanGraduate.toString());

        }
    }
}
