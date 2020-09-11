<?php


namespace iabuhilal\Salesforce\Models;

// https://developer.salesforce.com/docs/atlas.en-us.api_iot.meta/api_iot/qs_auth_access_token.htm
// https://www.site24x7.com/tools/json-to-php.html
class AccessTokenResponse
{
    public $access_token; //String
    public $instance_url; //String
    public $id; //String
    public $token_type; //String
    public $issued_at; //String
    public $signature; //String

}