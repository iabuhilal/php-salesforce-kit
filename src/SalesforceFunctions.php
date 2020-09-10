<?php

namespace iabuhilal\Salesforce;

use GuzzleHttp\Client;
/* use Exception\Salesforce as SalesforceException; */

class SalesforceFunctions
{
    protected $instance_url;
    protected $access_token;

    public function __construct()
    {
        if (!isset($_SESSION) and !isset($_SESSION['salesforce'])) {
            throw new SalesforceException('Access Denied', 403);
        }

        $this->instance_url = $_SESSION['salesforce']['instance_url'];
        $this->access_token = $_SESSION['salesforce']['access_token'];
    }

    public function query($query)
    {
        $url = "{$this->instance_url}/services/data/v39.0/query";

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
        $url = "{$this->instance_url}/services/data/v39.0/sobjects/{$object}/";

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
        $url = "{$this->instance_url}/services/data/v39.0/sobjects/{$object}/{$id}";

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
        $url = "{$this->instance_url}/services/data/v39.0/sobjects/{$object}/{$field}/{$id}";

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
        $url = "{$this->instance_url}/services/data/v39.0/sobjects/{$object}/{$id}";

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

        $url = "$this->instance_url/services/data/v45.0/sobjects/$strObjectName/describe";
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
        $url = "$this->instance_url/services/data/v45.0/jobs/ingest/";
        $data = [
            "object" => "Contact",
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
        $url = "$this->instance_url/services/data/v45.0/jobs/ingest";

        $client = new Client(['verify' => false ]);
        $request = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "OAuth $this->access_token"
            ]
        ]);

        $response = json_decode($request->getBody(), true);

        return $response;
    }



}
