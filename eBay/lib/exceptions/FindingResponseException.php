<?php

namespace eBay\lib\exceptions;

class FindingResponseException extends \Exception
{
    private $domain;
    private $subdomain;
    private $severity;
    private $category;
    
    public function __construct($message, $code, $domain, $severity, $category, $subdomain, $previous = null)
    {
        $this->domain = $domain;
        $this->severity = $severity;
        $this->category = $category;
        $this->subdomain = $subdomain;
        parent::__construct($message, $code, $previous);
    }
    
    public function getDomain()
    {
        return $this->domain;
    }

    public function getSubdomain()
    {
        return $this->subdomain;
    }

    public function getSeverity()
    {
        return $this->severity;
    }

    public function getCategory()
    {
        return $this->category;
    }


}
