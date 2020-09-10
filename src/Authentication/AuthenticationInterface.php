<?php

namespace iabuhilal\Salesforce\Authentication;

interface AuthenticationInterface
{
    public function getAccessToken();

    public function getInstanceUrl();
}

?>