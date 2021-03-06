# Php Salesforce Rest Api
this is based on 
```Bijesh Shrestha``` ```bjsmasth@gmail.com```
```iabuhilal``` ```php rest api```

## Install

Via Composer

``` bash
composer require iabuhilal/php-salesforce-kit
```

# Getting Started

Setting up a Connected App

1. Log into to your Salesforce org
2. Click on Setup in the upper right-hand menu
3. Under Build click ```Create > Apps ```
4. Scroll to the bottom and click ```New``` under Connected Apps.
5. Enter the following details for the remote application:
    - Connected App Name
    - API Name
    - Contact Email
    - Enable OAuth Settings under the API dropdown
    - Callback URL
    - Select access scope (If you need a refresh token, specify it here)
6. Click Save

After saving, you will now be given a Consumer Key and Consumer Secret. Update your config file with values for ```consumerKey``` and ```consumerSecret```

# Setup

Authentication

```bash
    $options = [
        'grant_type' => 'password',
        'client_id' => 'CONSUMERKEY',
        'client_secret' => 'CONSUMERSECRET',
        'username' => 'SALESFORCE_USERNAME',
        'password' => 'SALESFORCE_PASSWORD AND SECURITY_TOKEN'
    ];
    
    $salesforce = new iabuhilal\Salesforce\Authentication\PasswordAuthentication($options);
    $salesforce->authenticate();
    
    $access_token = $salesforce->getAccessToken();
    $instance_url = $salesforce->getInstanceUrl();
    
    Change Endpoint
    
    $salesforce = new iabuhilal\Salesforce\Authentication\PasswordAuthentication($options);
    $salesforce->setEndpoint('https://test.salesforce.com/');
    $salesforce->authenticate();
 
    $access_token = $salesforce->getAccessToken();
    $instance_url = $salesforce->getInstanceUrl();
```

Query

```bash
    $query = 'SELECT Id,Name FROM ACCOUNT LIMIT 100';
    
    $sfFunc = new \iabuhilal\Salesforce\SalesforceFunctions();
    $sfFunc->query($query);
```

Create

```bash
    
    $data = [
       'Name' => 'some name',
    ];
    
    $sfFunc->create('Account', $data);  #returns id
```

Update

```bash
    $new_data = [
       'Name' => 'another name',
    ];
    
    $sfFunc->update('Account', $id, $new_data); #returns status_code 204
    
```
Upsert

```bash
    $new_data = [
       'Name' => 'another name',
    ];
    
    $sfFunc->upsert('Account', 'API Name/ Field Name', 'value', $new_data); #returns status_code 204 or 201
    
```

Delete

```bash
    $sfFunc->delete('Account', $id);

```





#### Changelog: ####
##### 2020.09.09 #####
 - add closeJob function
##### 2020.09.09 #####
 - renamed class from CRUD to SalesforceFunctions
 - add create createJob, getAllJobs functions
 - add Salesforce description objects


