<?php

namespace iabuhilal\Salesforce;

use GuzzleHttp\Client;
use iabuhilal\Salesforce\Models\AccessTokenResponse;
use iabuhilal\Salesforce\Models\CreateJobResponse;
use iabuhilal\Salesforce\Models\GetJobInfoResponse;


use JsonMapper;


/* use Exception\Salesforce as SalesforceException; */

class CloseJobStateEnum
{
    const COMPLETE = 'UploadComplete';
    const ABORT = 'Aborted';

}

class JobStateEnum
{
    const Open = 'Open';
    const UploadComplete = 'UploadComplete';
    const InProgress = 'InProgress';
    const Aborted = 'Aborted';
    const JobComplete = 'JobComplete';
    const Failed = 'Failed';
}

class JobRecordsResultType
{
    const successfulResults = 'successfulResults';
    const failedResults = 'failedResults';
    const unprocessedrecords = 'unprocessedrecords';


}


class SalesforceFunctions
{
    protected $instance_url;
    protected $access_token;
    protected $tokenType;
    protected $accessTokenResponse;
    protected $apiVersion;



    public function __construct(AccessTokenResponse $accessTokenResponse, int $apiVersion)
    {

        $this->access_token     = $accessTokenResponse->getAccessToken();
        $this->instance_url     = $accessTokenResponse->getInstanceUrl();
        $this->tokenType        = $accessTokenResponse->getTokenType();

        $this->accessTokenResponse =$accessTokenResponse;
        $this->apiVersion  = ((int)$apiVersion ) . ".0";

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
    /*
     * createJob($jobSettings)
     * Creates a job, which represents a bulk operation (and associated data) that is
     * sent to Salesforce for asynchronous processing. Provide job data via an Upload
     * Job Data request, or as part of a multipart create job request.
     * https://developer.salesforce.com/docs/atlas.en-us.api_bulk_v2.meta/api_bulk_v2/create_job.htm
     * 
     */
    public function createJob($jobSettings) : CreateJobResponse
    {
        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/ingest/";

        $client = new Client(['verify' => false ]);
        $request = $client->request('POST', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token",
                'Content-type' => 'application/json ; charset=UTF-8',
                'Accept' => 'application/json'

            ],
            'json' => $jobSettings
        ]);

        $status = $request->getStatusCode();
        if ($status != 200) {
            die("Error: call to URL $url failed with status $status, response: " . $request->getReasonPhrase());
        }

       // $response = json_decode($request->getBody(), true);
        $response = json_decode($request->getBody());

        $mapper = new JsonMapper();
        $mapper->bStrictNullTypes = false;
        $createJobResponse = $mapper->map(
            $response,
            new CreateJobResponse()
        );

        // Set the API token returned by create job
        $this->apiVersion =  $createJobResponse->getApiVersion() . ".0";;

        return $createJobResponse;
        /*
        $response = json_decode($request->getBody(), true);
        var_dump($response);
        // Set the API token returned by create job
        $this->apiVersion =  $response['apiVersion'];

        return $response;
        */
    } // end createJob


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

    /*
     *  https://developer.salesforce.com/docs/atlas.en-us.api_bulk_v2.meta/api_bulk_v2/close_job.htm
     */
    public function closeJob($jobID, $jobStateEnum)
    {
        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/ingest/$jobID/";
        // var_dump($url);
        // var_dump($jobStateEnum);
        $client = new Client(['verify' => false ]);
        $headers = [
            'Authorization' => "OAuth $this->access_token",
            'Content-Type' => 'application/json; charset=UTF-8',
            'Accept' => 'application/json'
        ];
        $data = [
            "state" => $jobStateEnum
        ];

        $request = $client->request('PATCH', $url, [
            'headers' => $headers,
            'json' => $data
        ]);

        // var_dump($request);
        $response = json_decode($request->getBody(), true);

        // var_dump($response);
        /*
        foreach ($response as $key => $value)
        {
            echo "-->$key:$value<br/>";
        }
        */

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
        //var_dump($content);

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


    /* state
     * The current state of processing for the job. Values include:
     * Open             : The job has been created, and job data can be uploaded to the job.
     * UploadComplete   : All data for a job has been uploaded, and the job is ready to be queued and processed. No new data can be added to this job. You can’t edit or save a closed job.
     * InProgress       : The job is being processed by Salesforce. This includes automatic optimized chunking of job data and processing of job operations.
     * Aborted          : The job has been aborted. You can abort a job if you created it or if you have the “Manage Data Integrations” permission.
     * JobComplete      : The job was processed by Salesforce.
     * Failed           : Some records in the job failed. Job data that was successfully processed isn’t rolled back.
     */

    public function getJobInfo($jobID) : GetJobInfoResponse {

        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/ingest/$jobID";

        $headers = [
            'Authorization' => "OAuth $this->access_token",
            'Content-Type' => 'application/json; charset=UTF-8',
            'Accept' => 'application/json'
        ];

        $client = new Client(['verify' => false ]);
        $request = $client->request('GET', $url, [
            'headers' => $headers
        ]);


        $response = json_decode($request->getBody());

        $mapper = new JsonMapper();
        $mapper->bStrictNullTypes = false;
        $getJobInfoResponse = $mapper->map(
            $response,
            new GetJobInfoResponse()
        );


        return $getJobInfoResponse;
        

        /*
        $response = json_decode($request->getBody(), true);
        echo($request->getBody());
        return $response;
        */


    }


    public function getJobRecordResults($jobID,$jobRecordsResultType)  {

        $url = "$this->instance_url/services/data/v$this->apiVersion/jobs/ingest/$jobID/$jobRecordsResultType";

        $headers = [
            'Authorization' => "OAuth $this->access_token",
            'Content-Type' => 'application/json; charset=UTF-8',
            'Accept' => 'application/json'
        ];

        $client = new Client(['verify' => false ]);
        $request = $client->request('GET', $url, [
            'headers' => $headers
        ]);
        $response =$request->getBody();
        return $response;

    }






}
