<?php

/*
 * @author Jordi Rejas <github@rejas.eu>
 * 
 * Finding API class for making calls to ay´s Finding Platform
 */

namespace eBay\lib\requests;

class Finding extends Request
{
    private $arrayData = array();
    private $arrayFilters = array();
    private $arrayHeaders = array(
        "CONTENT-TYPE",
        "X-EBAY-SOA-GLOBAL-ID",
        "X-EBAY-SOA-MESSAGE-ENCODING",
        "X-EBAY-SOA-MESSAGE-PROTOCOL",
        "X-EBAY-SOA-OPERATION-NAME",
        "X-EBAY-SOA-REQUEST-DATA-FORMAT",
        "X-EBAY-SOA-RESPONSE-DATA-FORMAT",
        "X-EBAY-SOA-SECURITY-APPNAME",
        "X-EBAY-SOA-SERVICE-VERSION"       
    );
    private $arrayAcceptedItemFilters = array(
        "AuthorizedSellerOnly" => false,
        "AvailableTo" => false,
        "BestOfferOnly" => false,
        "CharityOnly" => false,
        "Condition" => true,
        "Currency" => false,
        "EndTimeFrom" => false,
        "EndTimeTo" => false,
        "ExcludeAutoPay" => false,
        "ExcludeCategory" => true,
        "ExcludeSeller" => true,
        "ExpeditedShippingType" => false,
        "FeaturedOnly" => false,
        "FeedbackScoreMax" => false,
        "FeedbackScoreMin" => false,
        "FreeShippingOnly" => false,
        "GetItFastOnly" => false,
        "HideDuplicateItems" => false,
        "ListedIn" => false,
        "ListingType" => true,
        "LocalPickupOnly" => false,
        "LocalSearchOnly" => false,
        "LocatedIn" => true,
        "LotsOnly" => false,
        "MaxBids" => false,
        "MaxDistance" => false,
        "MaxHandlingTime" => false,
        "MaxPrice" => false,
        "MaxQuantity" => false,
        "MinBids" => false,
        "MinPrice" => false,
        "MinQuantity" => false,
        "ModTimeFrom" => false,
        "OutletSellerOnly" => false,
        "PaymentMethod" => false,
        "ReturnsAcceptedOnly" => false,
        "Seller" => true,
        "SellerBusinessType" => false,
        "SoldItemsOnly" => false,
        "StartTimeFrom" => false,
        "StartTimeTo" => false,
        "TopRatedSellerOnly" => false,
        "ValueBoxInventory" => false,
        "WorldOfGoodOnly" => false);
    private $acceptedGlobalIds = array(
        'AT',
        'AU',
        'CH',
        'DE',
        'ENCA',
        'ES',
        'FR',
        'FRBE',
        'FRCA',
        'GB',
        'HK',
        'IE',
        'IN',
        'IT',
        'MOTOR',
        'MY',
        'NL',
        'NLBE',
        'PH',
        'PL',
        'SG',
        'US');
    private $arrayOutputSelectors = array();
    private $acceptedOutputSelectors = array(
        "SellerInfo",
        "StoreInfo",
        "AspectHistogram",
        "CategoryHistogram");
    private $sortOrder = null;
    private $acceptedSortOrders = array(
        "BestMatch",
        "BidCountFewest",
        "BidCountMost",
        "CountryAscending",
        "CountryDescending",
        "CurrentPriceHighest",
        "DistanceNearest",
        "EndTimeSoonest",
        "PricePlusShippingHighest",
        "PricePlusShippingLowest",
        "StartTimeNewest");
    private $entriesPerPage = null;
    private $pageNumber = null;

    public function __construct($appId, $sandbox = false)
    {
        parent::__construct($appId, $sandbox);
    }
    
    public function setPagination($entriesPerPage, $pageNumber)
    {
        $this->entriesPerPage = $entriesPerPage;
        $this->pageNumber = $pageNumber;
    }
    
    public function setGlobalId($globalId)
    {
        $globalId = strtoupper($globalId);
        
        if (!in_array(str_replace("EBAY-", "", $globalId), $this->acceptedGlobalIds)) {
            throw new Exception("Global-Id not allowed");
        }

        $this->_setGlobalId($globalId);
        return $this;
    }
    
    public function addOutputSelector($outputSelector)
    {
        if (!in_array($outputSelector, $this->acceptedOutputSelectors)) {
            throw new Exception("Output selector not allowed");
        }

        $this->arrayOutputSelectors[] = $outputSelector;
        return $this;
    }

    public function addFilter($filterName, $value, $paramName = null)
    {
        if(key_exists($filterName, $this->arrayAcceptedItemFilters)){
            
            if(!$this->arrayAcceptedItemFilters[$filterName]){
                
                $this->arrayFilters[$filterName] = $value;
                
            }else{
                
                $this->arrayFilters[$filterName][] = $value;
            }
        }
        return $this;
    }

    public function addAditionalData($key, $value)
    {
        $this->arrayData[$key] = $value;
        return $this;
    }

    public function setSortOrder($sortOrder)
    {
        if (!in_array($sortOrder, $this->acceptedSortOrders)) {
            throw new Exception("Sort order not allowed");
        }

        $this->sortOrder = $sortOrder;
        return $this;
    }
    
    public function getSearchKeywordsRecommendation($keywords)
    {
        $this->arrayData['keywords'] = $keywords;
        $this->_setRequest('getSearchKeywordsRecommendationRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findCompletedItems($keywords)
    {
        $this->arrayData['keywords'] = $keywords;
        $this->_setRequest('findCompletedItemsRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findItemsByKeywords($keywords)
    {
        $this->arrayData['keywords'] = $keywords;
        $this->_setRequest('findItemsByKeywordsRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findItemsByCategory($categoryId)
    {
        $this->arrayData['categoryId'] = $categoryId;
        $this->_setRequest('findItemsByCategoryRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findItemsAdvanced($keywords)
    {
        $this->arrayData['keywords'] = $keywords;
        $this->_setRequest('findItemsAdvancedRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findItemsByProduct($productIdType, $productId)
    {
        $this->arrayData['productId.@type'] = $productIdType;
        $this->arrayData['productId'] = $productId;
        $this->_setRequest('findItemsByProductRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function findItemsIneBayStores($storeName)
    {
        $this->arrayData['storeName'] = $storeName;
        $this->_setRequest('findItemsIneBayStoresRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function getHistograms($categoryId)
    {
        $this->arrayData['categoryId'] = $categoryId;
        $this->_setRequest('getHistogramsRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    public function getVersion()
    {
        $this->_setRequest('getVersionRequest');
        $this->_configureRequest();
        return $this->_send();
    }

    private function _configureRequest()
    {
        $globalId = $this->getGlobalId();
        
        switch ($this->getRequestProtocol()) {

            case 'post':

                // Headers
                $headers = array(
                    "{$this->arrayHeaders[4]}: {$this->_determiningRequest()}",
                    //"X-EBAY-SOA-SERVICE-VERSION: 1.3.0",
                    "{$this->arrayHeaders[5]}: {$this->getRequestFormat()}",
                    "{$this->arrayHeaders[6]}: {$this->getResponseFormat()}",
                    "{$this->arrayHeaders[7]}: {$this->getAppId()}"
                );
                
                if(!empty($globalId)){
                    $headers[] = "{$this->arrayHeaders[1]}: $globalId";
                }
                
                //Post data
                switch ($this->getRequestFormat()) {
                    
                    case 'json':

                        $data = '{"jsonns.xsi":"http://www.w3.org/2001/XMLSchema-instance",
                        "jsonns.xs":"http://www.w3.org/2001/XMLSchema",
                        "jsonns.tns":"http://www.ebay.com/marketplace/search/v1/services",
                        "tns.' . $this->_determiningRequest() . 'Request":';
                        
                        if(count($this->arrayData) > 0){
                            $data .= json_encode($this->arrayData);
                        }
                        
                        $data .= '}';
                        
                        break;
                    
                    case 'xml':
                        
                        $arrayData = $this->arrayData;
                        
                        if($this->sortOrder !== null){
                            $arrayData['sortOrder'] = $this->sortOrder;
                        }
                
                        if($this->entriesPerPage !== null){
                            $arrayData['paginationInput'] = array('pageNumber' => $this->pageNumber, 'entriesPerPage' => $this->entriesPerPage);
                        }
                        
						$productIdType = null;
						$productId = null;
						
                        if(isset($arrayData['productId'])){
							$productId = $arrayData['productId'];
                            $productIdType = $arrayData['productId.@type'];
                            unset($arrayData['productId'], $arrayData['productId.@type']);
                        }
                        
                        $xml = $this->_createXml($arrayData, $this->_determiningRequest(), 'http://www.ebay.com/marketplace/search/v1/services');

                        $xml = $this->_addProductIdToXml($xml, $productIdType, $productId);

                        $xml = $this->_addOutputSelectorsToXml($xml);

                        $data = $this->_addItemFiltersToXml($xml);
                        
                        break;

                    default:
                        break;
                }

                $this->_setHeaders($headers);
                $this->_setPostData($data);

                break;

            case 'get':

                $headers = "?{$this->arrayHeaders[7]}={$this->getAppId()}&{$this->arrayHeaders[4]}={$this->_determiningRequest()}&{$this->arrayHeaders[6]}={$this->getResponseFormat()}";

                if(!empty($globalId)){
                    $headers .= "&{$this->arrayHeaders[1]}=$globalId";
                }
                
                if(count($this->getArrayAffiliate()) > 0){
                    
                }
                
                $url = $this->getUrl() . $headers . $this->_generateParamenters($this->arrayData) . $this->_generaterFilterParameters().$this->_generateOutputSelectorParameters();
                
                if($this->sortOrder !== null){
                    $url .= '&sortOrder=' . $this->sortOrder;
                }
                
                if($this->entriesPerPage !== null){
                    $url .= '&paginationInput.pageNumber=' . $this->pageNumber . '&paginationInput.entriesPerPage=' . $this->entriesPerPage;
                }
                
                $this->_setUrl(str_replace(" ", "%20", $url));

                break;
        }
    }

    private function _generaterFilterParameters()
    {
        $filters = '';
        $i = 0;
        
        if(count($this->arrayFilters) > 0){
            
            foreach ($this->arrayFilters as $name => $value) {

                switch ($this->arrayAcceptedItemFilters[$name]) {

                    case true:

                        $filters .= '&itemFilter(' . $i . ').name=' . $name;
                        $j=0;

                        if(is_array($value)){
                            
                            foreach($value as $key => $value2){
                                
                                $filters .= '&itemFilter(' . $i . ').value(' . $j++ . ')=' . $value2;
                            
                                
                            }
                       
                        }

                        break;

                    case false:

                        $filters .= '&itemFilter.name=' . $name . '&itemFilter.value=' . $value;

                        break;

                    default:
                        break;
                }
            }
        }

        return $filters;
    }
    
    private function _addItemFiltersToXml($xml)
    {
        if(count($this->arrayFilters) == 0){
            return $xml;
        }
            
        $doc = new DOMDocument();
        $doc->loadXML($xml);
        $rootElement = $doc->documentElement;

        foreach ($this->arrayFilters as $name => $value) {

            $domElement = $doc->createElement('itemFilter');

            switch ($this->arrayAcceptedItemFilters[$name]) {

                case true:

                    if(is_array($value)){

                        $domElementName = new DOMElement('name', $name);
                        $domElement->appendChild($domElementName);

                        foreach($value as $key => $value2){

                            $domElementValue = new DOMElement('value', $value2);
                            $domElement->appendChild($domElementValue);

                        }


                    }

                    break;

                case false:

                    $domElementName = new DOMElement('name', $name);
                    $domElement->appendChild($domElementName);
                    $domElementValue = new DOMElement('value', $value);
                    $domElement->appendChild($domElementValue);

                    break;

                default:
                    break;
            }

            $rootElement->appendChild($domElement);
        }

        return $doc->saveXML();
    }
    
    private function _generateOutputSelectorParameters()
    {
		$outputSelectors = '';
		
        if(count($this->arrayOutputSelectors) > 0){
            
            $i = 0;
            
            foreach ($this->arrayOutputSelectors as $value) {
                
                $outputSelectors .= '&outputSelector('. $i++ .')=' . $value;
                
            }
        }
        
        return $outputSelectors;
    }
    
    private function _addOutputSelectorsToXml($xml)
    {
        if(count($this->arrayOutputSelectors) > 0){
            return $xml;
        }
        
        $doc = new \SimpleXMLElement($xml);

        foreach ($this->arrayOutputSelectors as $value) {

            $doc->addChild('outputSelector', $value);

        }

        return $doc->asXML();
    }
        
    private function _generateAffiliateParameters()
    {
        
    }
    
    private function _determiningRequest()
    {
        $arrayDebugBacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return $arrayDebugBacktrace[2]['function'];
    }
}