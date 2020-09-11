<?php

namespace iabuhilal\Salesforce;

use GuzzleHttp\Client;
/* use Exception\Salesforce as SalesforceException; */

class SalesforceFunctions
{
    protected $instance_url;
    protected $access_token;
    protected $tokenType;
    protected $salesforce;
    protected $apiVersion;



    public function __construct($salesforce)
    {
        /*
        if (!isset($_SESSION) and !isset($_SESSION['salesforce'])) {
            throw new SalesforceException('Access Denied', 403);
        }

        $this->instance_url = $_SESSION['salesforce']['instance_url'];
        $this->access_token = $_SESSION['salesforce']['access_token'];
        $this->apiVersion = $_SESSION['salesforce']['apiVersion'];
        */
        $this->salesforce =$salesforce;


        $this->access_token = $salesforce->getAccessToken();
        $this->instance_url = $salesforce->getInstanceUrl();
        $this->tokenType =  $salesforce->getTokenType();
        $this->apiVersion  = "49.0";




    }

    public function getApiVersion($apiVersion)
    {
        return  ((int)$apiVersion ) . ".0";
    }

    public function query($query)
    {
        $url = "{$this->instance_url}/services/data/v$this->apiVersion/query";

        $client = new Client();
        $request = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "OAuth {$this->access_token}"
            ],
            'query' => [
                'q' => $query
            ]
        ]);

        return json_decode($request->getBody(), true);
    }

    public function create($object, array $data)
    {
        $url = "{$this->instance_url}/services/data/v$this->apiVersion/sobjects/{$object}/";

        $client = new Client();

        $request = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "OAuth {$this->access_token}",
                'Content-type' => 'application/json'
            ],
            'json' => $data
        ]);

        $status = $request->getStatusCode();

        if ($status != 201) {
            throw new SalesforceException(
                "Error: call to URL {$url} failed with status {$status}, response: {$request->getReasonPhrase()}"
            );
        }

        $response = json_decode($request->getBody(), true);
        $id = $response["id"];

        return $id;

    }

    public function update($object, $id, array $data)
    {
        $url = "{$this->instance_url}/services/data/v$this->apiVersion/sobjects/{$object}/{$id}";

        $client = new Client();

        $request = $client->request('PATCH', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token",
                'Content-type' => 'application/json'
            ],
            'json' => $data
        ]);

        $status = $request->getStatusCode();

        if ($status != 204) {
            throw new SalesforceException(
                "Error: call to URL {$url} failed with status {$status}, response: {$request->getReasonPhrase()}"
            );
        }

        return $status;
    }

    public function upsert($object, $field, $id, array $data)
    {
        $url = "{$this->instance_url}/services/data/v$this->apiVersion/sobjects/{$object}/{$field}/{$id}";

        $client = new Client();

        $request = $client->request('PATCH', $url, [
            'headers' => [
                'Authorization' => "OAuth {$this->access_token}",
                'Content-type' => 'application/json'
            ],
            'json' => $data
        ]);

        $status = $request->getStatusCode();

        if ($status != 204 && $status != 201) {
            throw new SalesforceException(
                "Error: call to URL {$url} failed with status {$status}, response: {$request->getReasonPhrase()}"
            );
        }

        return $status;
    }

    public function delete($object, $id)
    {
        $url = "{$this->instance_url}/services/data/v$this->apiVersion/sobjects/{$object}/{$id}";

        $client = new Client();
        $request = $client->request('DELETE', $url, [
            'headers' => [
                'Authorization' => "OAuth {$this->access_token}",
            ]
        ]);

        $status = $request->getStatusCode();

        if ($status != 204) {
            throw new SalesforceException(
                "Error: call to URL {$url} failed with status {$status}, response: {$request->getReasonPhrase()}"
            );
        }

        return true;
    }


    public function describe($strObjectName) {

        $url = "$this->instance_url/services/data/v$this->apiVersion/sobjects/$strObjectName/describe";
        $client = new Client(['verify' => false ]);
        $request = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token"
            ]
        ]);

        return json_decode($request->getBody(), true);



    }



    public function createJob()
    {
        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/ingest/";

        //var_dump($url);

        $data = [
            "object" => "Account",
            "contentType" => "CSV",
            "operation" => "insert",
            "lineEnding" => "CRLF"
        ];

        $client = new Client(['verify' => false ]);
        $request = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token",
                'Content-type' => 'application/json ; charset=UTF-8',
                'Accept' => 'application/json'

            ],
            'json' => $data
        ]);


        // var_dump($request->getBody());

        $status = $request->getStatusCode();

        if ($status != 200) {
            die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());
        }


        $response = json_decode($request->getBody(), true);

        // var_dump($response);

        $id = $response["id"];
        /* response sample
          'id' => string '7503i000009NRgkAAG' (length=18)
          'operation' => string 'insert' (length=6)
          'object' => string 'Contact' (length=7)
          'createdById' => string '0053i000000qAL7AAM' (length=18)
          'createdDate' => string '2020-09-09T17:59:11.000+0000' (length=28)
          'systemModstamp' => string '2020-09-09T17:59:11.000+0000' (length=28)
          'state' => string 'Open' (length=4)
          'concurrencyMode' => string 'Parallel' (length=8)
          'contentType' => string 'CSV' (length=3)
          'apiVersion' => float 45
          'contentUrl' => string 'services/data/v45.0/jobs/ingest/7503i000009NRgkAAG/batches' (length=58)
          'lineEnding' => string 'CRLF' (length=4)
          'columnDelimiter' => string 'COMMA' (length=5)
          */
        return $response;

    }


    public function getAllJobs()
    {
        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/query";
        /*
        $url &= "?isPkChunkingEnabled=isPkChunkingEnabled";
        $url &= "&jobType=Classic";
        $url &= "&concurrencyMode=concurrencyMode";
        $url &= "&queryLocator=queryLocator";
        */

        $data = [
            "jobType" => "V2Query",
            "contentType" => "CSV",
            "operation" => "insert",
            "lineEnding" => "CRLF"
        ];


        $client = new Client(['verify' => false ]);


        $request = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token"
            ]
        ]);


        var_dump($request);
        $response = json_decode($request->getBody(), true);

        return $response;
    }

    public function closeJob($jobID, $apiVersion)
    {
        // https://developer.salesforce.com/docs/atlas.en-us.api_bulk_v2.meta/api_bulk_v2/close_job.htm
        $url = "$this->instance_url/services/data/v$apiVersion/jobs/ingest/$jobID/";
        // var_dump($url);

        $client = new Client(['verify' => false ]);

        $headers = [
            'Authorization' => "OAuth $this->access_token",
            'Content-Type' => 'application/json; charset=UTF-8',
            'Accept' => 'application/json'
        ];

        $data = [
            "state" => "UploadComplete"
        ];



        $request = $client->request('PATCH', $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        // var_dump($request);
        $response = json_decode($request->getBody(), true);

         var_dump($response);

        foreach ($response as $key => $value)
        {
            echo "-->$key:$value<br/>";
        }


        /*
         * array (size=10)
              'id' => string '7503i000009NaCtAAK' (length=18)
              'operation' => string 'insert' (length=6)
              'object' => string 'Account' (length=7)
              'createdById' => string '0053i000000qAL7AAM' (length=18)
              'createdDate' => string '2020-09-11T03:32:50.000+0000' (length=28)
              'systemModstamp' => string '2020-09-11T03:32:50.000+0000' (length=28)
              'state' => string 'UploadComplete' (length=14)
              'concurrencyMode' => string 'Parallel' (length=8)
              'contentType' => string 'CSV' (length=3)
              'apiVersion' => float 49
         */

        return $response;




    }
    public function uploadCSV($jobID, $contentUrl,$content)
    {
        // https://developer.salesforce.com/docs/atlas.en-us.api_bulk_v2.meta/api_bulk_v2/upload_job_data.htm
        var_dump($content);

        $url = "$this->instance_url/$contentUrl/";
       //  var_dump($url);
        $client = new Client(['verify' => false ]);

        $headers = [
            'Authorization' => "OAuth $this->access_token",
            'Content-Type' => 'text/csv',
            'Accept' => 'application/json'
        ];

        $request = $client->request('PUT', $url, [
            'headers' => $headers,
            'body' =>$content

        ]);

        $status = $request->getStatusCode();

        if ($status != 201) {
            // None. Returns a status code of 201 (Created), which indicates that the job data was successfully received by Salesforce.
            die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());

        }

        return true;

    }






}
