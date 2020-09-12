<?php


namespace iabuhilal\Salesforce\Models;

// https://developer.salesforce.com/docs/atlas.en-us.api_iot.meta/api_iot/qs_auth_access_token.htm
// https://www.site24x7.com/tools/json-to-php.html
class AccessTokenResponse
{
    private $access_token; //String
    private $instance_url; //String
    private $id; //String
    private $token_type; //String
    private $issued_at; //String
    private $signature; //String


    public function setAccessToken($value)
    {
        $this->access_token = $value;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }


    public function setInstanceUrl($value)
    {
        $this->instance_url = $value;
    }

    public function getInstanceUrl()
    {
        return $this->instance_url;
    }


    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setTokenType($value)
    {
        $this->token_type = $value;
    }

    public function getTokenType()
    {
        return $this->token_type;
    }


    public function setIssuedAt($value)
    {
        $this->issued_at = $value;
    }

    public function getIssuedAt()
    {
        return $this->issued_at;
    }


    public function setSignature($value)
    {
        $this->signature = $value;
    }

    public function getSignature()
    {
        return $this->signature;
    }

}