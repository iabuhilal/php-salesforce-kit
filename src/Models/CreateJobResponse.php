<?php


namespace iabuhilal\Salesforce\Models;


class CreateJobResponse
{
    private $id; //String
    private $operation; //String
    private $object; //String
    private $createdById; //String
    private $createdDate; //Date
    private $systemModstamp; //Date
    private $state; //String
    private $concurrencyMode; //String
    private $contentType; //String
    private $apiVersion; //int
    private $contentUrl; //String
    private $lineEnding; //String
    private $columnDelimiter; //String

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setOperation($value)
    {
        $this->operation = $value;
    }

    public function getOperation()
    {
        return $this->operation;
    }


    public function setObject($value)
    {
        $this->object = $value;
    }

    public function getObject()
    {
        return $this->object;
    }


    public function setCreatedById($value)
    {
        $this->createdById = $value;
    }

    public function getCreatedById()
    {
        return $this->createdById;
    }


    public function setCreatedDate($value)
    {
        $this->createdDate = $value;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }


    public function setSystemModstamp($value)
    {
        $this->systemModstamp = $value;
    }

    public function getSystemModstamp()
    {
        return $this->systemModstamp;
    }


    public function setState($value)
    {
        $this->state = $value;
    }

    public function getState()
    {
        return $this->state;
    }


    public function setConcurrencyMode($value)
    {
        $this->concurrencyMode = $value;
    }

    public function getConcurrencyMode()
    {
        return $this->concurrencyMode;
    }


    public function setContentType($value)
    {
        $this->contentType = $value;
    }

    public function getContentType()
    {
        return $this->contentType;
    }


    public function setApiVersion($value)
    {
        $this->apiVersion = $value;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }


    public function setContentUrl($value)
    {
        $this->contentUrl = $value;
    }

    public function getContentUrl()
    {
        return $this->contentUrl;
    }


    public function setLineEnding($value)
    {
        $this->lineEnding = $value;
    }

    public function getLineEnding()
    {
        return $this->lineEnding;
    }


    public function setColumnDelimiter($value)
    {
        $this->columnDelimiter = $value;
    }

    public function getColumnDelimiter()
    {
        return $this->columnDelimiter;
    }



}