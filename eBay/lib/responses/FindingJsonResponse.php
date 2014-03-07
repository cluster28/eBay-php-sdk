<?php

namespace eBay\lib\responses;

use eBay\lib\exceptions\FindingResponseException;

class FindingJsonResponse extends Response
{
    public function __construct($originalResponse, $arrayInfo)
    {
        parent::__construct($originalResponse, $arrayInfo);
        $objResponse = json_decode($originalResponse);
        $this->_setArrayResponse($this->object_to_array($objResponse));
        $this->_extractData();
    }

	private function _extractData()
    {
        $arrayResponse = $this->getArrayResponse();
        $arrayValues = array_values($arrayResponse);
        $arrayValues = $arrayValues[0];
        
        if($arrayValues[0]['ack'][0] !== 'Success'){
            
            throw new FindingResponseException(
                    $arrayValues[0]['errorMessage'][0]['error'][0]['message'][0],
                    $arrayValues[0]['errorMessage'][0]['error'][0]['errorId'][0],
                    $arrayValues[0]['errorMessage'][0]['error'][0]['domain'][0],
                    $arrayValues[0]['errorMessage'][0]['error'][0]['severity'][0],
                    $arrayValues[0]['errorMessage'][0]['error'][0]['category'][0],
                    $arrayValues[0]['errorMessage'][0]['error'][0]['subdomain'][0]
                    );
            
        }
        
        $this->_setAck($arrayValues[0]['ack'][0]);
        $this->_setVersion($arrayValues[0]['version'][0]);
        $this->_setTimestamp($arrayValues[0]['timestamp'][0]);
        
        if (isset($arrayValues[0]['searchResult'])) {
            $this->_setArrayItems($arrayValues[0]['searchResult'][0]['item']);
            $this->_setTotalResults((int) $arrayValues[0]['searchResult'][0]['@count']);
        }

        if (isset($arrayValues[0]['keywords'])) {

            $this->_setKeywords($arrayValues[0]['keywords'][0]);
            $this->_setTotalResults(1);
        }
        
        $this->_setPageNumber($arrayValues[0]['paginationOutput'][0]['pageNumber'][0]);
        $this->_setEntriesPerPage($arrayValues[0]['paginationOutput'][0]['entriesPerPage'][0]);
        $this->_setTotalPages($arrayValues[0]['paginationOutput'][0]['totalPages'][0]);
        $this->_setTotalEntries($arrayValues[0]['paginationOutput'][0]['totalEntries'][0]);
        $this->_setItemSearchUrl($arrayValues[0]['itemSearchURL'][0]);
    }
    
    public function getArrayItemObjects()
    {
        $arrayItemObjects = array();
        
        $arrayItems = $this->getArrayItems();
        
        if (count($arrayItems) > 0) {
            
            foreach ($arrayItems as $key => $value) {

                $item = new Item();
                $item->setItemId($value['itemId'][0])
                        ->setTitle($value['title'][0])
                        ->setSubTitle($value['subtitle'][0])
                        ->setGlobalId($value['globalId'][0])
                        ->setGalleryURL($value['galleryURL'][0])
                        ->setViewItemURL($value['viewItemURL'][0])
                        ->setAutoPay($value['autoPay'][0])
                        ->setPostalCode($value['postalCode'][0])
                        ->setLocation($value['location'][0])
                        ->setCountry($value['country'][0])
                        ->setReturnsAccepted($value['returnsAccepted'][0])
                        ->setIsMultiVariationListing($value['isMultiVariationListing'][0])
                        ->setTopRatedListing($value['topRatedListing'][0])
                        ->setCharityId($value['charityId'][0])
                        ->setCompatibility($value['compatibility'][0])
                        ->setGalleryPlusPictureURL($value['galleryPlusPictureURL'][0])
                        ->setPictureURLLarge($value['pictureURLLarge'][0])
                        ->setPictureURLSuperSize($value['pictureURLSuperSize'][0])

                ;

                if (isset($value['paymentMethod'])) {

                    $arrayPaymentMethod = array();
                    foreach ($value['paymentMethod'] as $paymentMethod) {
                        $arrayPaymentMethod[] = $paymentMethod;
                    }
                    $item->setArrayPaymentMethod($arrayPaymentMethod);
                }

                if (isset($value['shippingInfo'])) {

                    $array = array();
                    $array['shippingServiceCost'] = array('currencyId' => $value['shippingInfo'][0]['shippingServiceCost'][0]['@currencyId'],
                        'value' => $value['shippingInfo'][0]['shippingServiceCost'][0]['__value__']);
                    $array['shippingType'] = $value['shippingInfo'][0]['shippingType'][0];
                    $array['shipToLocations'] = $value['shippingInfo'][0]['shipToLocations'];
                    $array['expeditedShipping'] = $value['shippingInfo'][0]['expeditedShipping'][0];
                    $array['oneDayShippingAvailable'] = $value['shippingInfo'][0]['oneDayShippingAvailable'][0];
                    $array['handlingTime'] = $value['shippingInfo'][0]['handlingTime'][0];
                    $item->setArrayShippingInfo($array);
                }

                if (isset($value['sellingStatus'])) {

                    $array = array();
                    $array['currentPrice'] = array('currencyId' => $value['sellingStatus'][0]['currentPrice'][0]['@currencyId'],
                        'value' => $value['sellingStatus'][0]['currentPrice'][0]['__value__']);
                    $array['convertedCurrentPrice'] = array('currencyId' => $value['sellingStatus'][0]['convertedCurrentPrice'][0]['@currencyId'],
                        'value' => $value['sellingStatus'][0]['convertedCurrentPrice'][0]['__value__']);
                    $array['sellingState'] = $value['sellingStatus'][0]['sellingState'][0];
                    $item->setArraySellingStatus($array);
                }

                if (isset($value['listingInfo'])) {

                    $array = array();
                    $array['bestOfferEnabled'] = $value['listingInfo'][0]['bestOfferEnabled'][0];
                    $array['buyItNowAvailable'] = $value['listingInfo'][0]['buyItNowAvailable'][0];
                    $array['startTime'] = $value['listingInfo'][0]['startTime'][0];
                    $array['endTime'] = $value['listingInfo'][0]['endTime'][0];
                    $array['listingType'] = $value['listingInfo'][0]['listingType'][0];
                    $array['gift'] = $value['listingInfo'][0]['gift'][0];
                    $item->setArrayListingInfo($array);
                }

                if (isset($value['condition'])) {

                    $array = array();
                    $array['conditionId'] = $value['condition'][0]['conditionId'][0];
                    $array['conditionDisplayName'] = $value['condition'][0]['conditionDisplayName'][0];
                    $item->setArrayCondition($array);
                }

                $arrayCategories = array();

                if (isset($value['primaryCategory'])) {

                    $array = array();
                    $array['categoryId'] = $value['primaryCategory'][0]['categoryId'][0];
                    $array['categoryName'] = $value['primaryCategory'][0]['categoryName'][0];
                    $arrayCategories['primary'] = $array;
                }

                if (isset($value['secondaryCategory'])) {

                    $array = array();
                    $array['categoryId'] = $value['secondaryCategory'][0]['categoryId'][0];
                    $array['categoryName'] = $value['secondaryCategory'][0]['categoryName'][0];
                    $arrayCategories['secondary'] = $array;
                }

                $item->setArrayCategories($arrayCategories);

                if (isset($value['sellerInfo'])) {
                    $array = array();
                    $array['sellerUserName'] = $value['sellerInfo'][0]['sellerUserName'][0];
                    $array['feedbackScore'] = $value['sellerInfo'][0]['feedbackScore'][0];
                    $array['positiveFeedbackPercent'] = $value['sellerInfo'][0]['positiveFeedbackPercent'][0];
                    $array['feedbackRatingStar'] = $value['sellerInfo'][0]['feedbackRatingStar'][0];
                    $array['topRatedSeller'] = $value['sellerInfo'][0]['topRatedSeller'][0];
                    $item->setArraySellerInfo($array);
                }

                if (isset($value['storeInfo'])) {
                    $array = array();
                    $array['storeName'] = $value['storeInfo'][0]['storeName'][0];
                    $array['storeURL'] = $value['storeInfo'][0]['storeURL'][0];
                    $item->setArrayStoreInfo($array);
                }

                if (isset($value['distance'])) {
                    $item->setArrayDistance(array('unit' => $value['distance'][0]['@unit'],
                        'value' => $value['distance'][0]['__value__']));
                }

                $arrayItemObjects[] = $item;
            }
        }
    
        return $arrayItemObjects;
    }
}
