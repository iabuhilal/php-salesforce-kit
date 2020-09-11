<?php

namespace iabuhilal\Salesforce\Authentication;

use iabuhilal\Salesforce\Exception\SalesforceAuthentication;
use GuzzleHttp\Client;
use iabuhilal\Salesforce\Models\AccessTokenResponse;
use Netresearch\JsonMapper;

class PasswordAuthentication implements AuthenticationInterface
{
    protected $client;
    protected $end_point;
    protected $options;

    protected $access_token;
    protected $instance_url;
    protected $token_type;
    protected $issued_at;

    protected $accessTokenResponse;

    public function __construct(array $options, $is_production)
    {
        $this->is_production =$is_production;
        $this->end_point = $is_production ? 'https://login.salesforce.com/' : 'https://test.salesforce.com/';
        $this->options = $options;
    }

    public function authenticate()
    {
        $client = new Client();
        $request = $client->request('post', "{$this->end_point}services/oauth2/token", ['form_params' => $this->options]);

        $response = json_decode($request->getBody(), true);

        if ($response) {
            /*
            $this->access_token = $response['access_token'];
            $this->instance_url = $response['instance_url'];
            $this->token_type = $response['token_type'];
            $this->issued_at = $response['issued_at'];
            */
            $mapper = new JsonMapper();
            $mapper->bStrictNullTypes = false;
            $accessTokenResponse = $mapper->map(
                $response,
                new AccessTokenResponse()
            );
            $this->accessTokenResponse = $accessTokenResponse;
            return $accessTokenResponse;

        } else {
            throw new SalesforceAuthentication($request->getBody());
        }
    }

    public function getAccessTokenResponse()
    {
        return $this->accessTokenResponse;
    }

    public function getAccessToken()
    {
        return $this->accessTokenResponse->access_token;
    }

    public function getInstanceUrl()
    {
        return $this->accessTokenResponse->instance_url;
    }

    public function getTokenType()
    {
        return $this->accessTokenResponse->token_type;
    }


}

?>
