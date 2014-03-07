<?php

namespace eBay\lib\responses;

abstract class Response
{

    private $originalResponse = null;
    private $arrayResponse = null;
    private $arrayInfo = null;
    private $responseType = null;
    private $ack = null;
    private $version = null;
    private $timestamp = null;
    private $arrayItems = array();
    private $arrayProducts = array();
    private $totalResults = 0;
    private $keywords = null;
    
    private $pageNumber = 0;
    private $entriesPerPage = 0;
    private $totalPages = 0;
    private $totalEntries = 0;
    
    private $itemSearchUrl = null;

    public function __construct($originalResponse, $arrayInfo)
    {
        $this->originalResponse = $originalResponse;
        $this->arrayInfo = $arrayInfo;
        $this->responseType = preg_match('/json/i', get_called_class()) ? 'JSON' : 'XML' ;
    }
    
    public function getArrayInfo()
    {
        return $this->arrayInfo;
    }
    
    public function getOriginalResponse()
    {
        return $this->originalResponse;
    }

    protected function _setArrayResponse($arrayResponse)
    {
        $this->arrayResponse = $arrayResponse;
    }

    public function getArrayResponse()
    {
        return $this->arrayResponse;
    }

    public function getAck()
    {
        return $this->ack;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getArrayItems()
    {
        return $this->arrayItems;
    }

    public function getTotalResults()
    {
        return $this->totalResults;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    public function getEntriesPerPage()
    {
        return $this->entriesPerPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getTotalEntries()
    {
        return $this->totalEntries;
    }

    public function getItemSearchUrl()
    {
        return $this->itemSearchUrl;
    }

    protected function _setItemSearchUrl($itemSearchUrl)
    {
        $this->itemSearchUrl = $itemSearchUrl;
    }

    protected function _setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
    }

    protected function _setEntriesPerPage($entriesPerPage)
    {
        $this->entriesPerPage = $entriesPerPage;
    }

    protected function _setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    protected function _setTotalEntries($totalEntries)
    {
        $this->totalEntries = $totalEntries;
    }

    protected function _setAck($ack)
    {
        $this->ack = $ack;
    }

    protected function _setVersion($version)
    {
        $this->version = $version;
    }

    protected function _setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    protected function _setArrayItems($arrayItems)
    {
        $this->arrayItems = $arrayItems;
    }
    
    protected function _setArrayProducts($arrayProducts)
    {
        $this->arrayProducts = $arrayProducts;
    }

    protected function _setTotalResults($totalResults)
    {
        $this->totalResults = $totalResults;
    }

    protected function _setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
    
    protected function object_to_array($obj)
    {

        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;

        foreach ($arrObj as $key => $val) {

            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }

        return $arr;
    }
}
