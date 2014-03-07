<?php

namespace eBay\lib\responses;

use eBay\lib\exceptions\FindingResponseException;

class FindingXmlResponse extends Response
{
    public function __construct($originalResponse, $arrayInfo)
    {
        parent::__construct($originalResponse, $arrayInfo);
        $this->_setArrayResponse($this->_xml2array($originalResponse));
        $this->_extractData();
    }

    private function _xml2array($xml, $get_attributes = 1, $priority = 'tag')
    {
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($xml), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
            return; //Hmm...
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $current = & $xml_array;
        $repeated_tag_index = array();
        foreach ($xml_values as $data) {
            unset($attributes, $value);
            extract($data);
            $result = array();
            $attributes_data = array();
            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else
                    $result['value'] = $value;
            }
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else
                        $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }
            if ($type == "open") {
                $parent[$level - 1] = & $current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = & $current[$tag];
                }
                else {
                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = & $current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                }
                else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else {
                        $current[$tag] = array(
                            $current[$tag],
                            $result
                        );
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') {
                $current = & $parent[$level - 1];
            }
        }
        return ($xml_array);
    }

    protected function _extractData()
    {
        $arrayResponse = $this->getArrayResponse();
        $arrayValues = array_values($arrayResponse);
        $arrayValues = $arrayValues[0];
//        echo __FILE__.": ".__LINE__.'<pre>'.print_r($arrayValues, true).'</pre>';  
        
        if($arrayValues['ack'] !== 'Success'){
            
            throw new FindingResponseException(	  $arrayValues['errorMessage']['error']['message'],
												  $arrayValues['errorMessage']['error']['errorId'],
												  $arrayValues['errorMessage']['error']['domain'],
												  $arrayValues['errorMessage']['error']['severity'],
												  $arrayValues['errorMessage']['error']['category'],
												  $arrayValues['errorMessage']['error']['subdomain']
												  );
            
        }
        
        $this->_setAck($arrayValues['ack']);
        $this->_setVersion($arrayValues['version']);
        $this->_setTimestamp($arrayValues['timestamp']);

        if (isset($arrayValues['searchResult'])) {

            $this->_setArrayItems($arrayValues['searchResult']['item']);
            $this->_setTotalResults((int) $arrayValues['searchResult_attr']['count']);
        }

        if (isset($arrayValues['keywords'])) {

            $this->_setKeywords($arrayValues['keywords']);
            $this->_setTotalResults(1);
        }
        
        $this->_setPageNumber($arrayValues['paginationOutput']['pageNumber']);
        $this->_setEntriesPerPage($arrayValues['paginationOutput']['entriesPerPage']);
        $this->_setTotalPages($arrayValues['paginationOutput']['totalPages']);
        $this->_setTotalEntries($arrayValues['paginationOutput']['totalEntries']);
        $this->_setItemSearchUrl($arrayValues['itemSearchURL']);
    }
    
    public function getArrayItemObjects()
    {
        $arrayItemObjects = array();
        
        $arrayItems = $this->getArrayItems();
        
        if (count($arrayItems) > 0) {
            
            foreach ($arrayItems as $key => $value) {

                $item = new Item();
                $item->setItemId($value['itemId'])
                        ->setTitle($value['title'])
                        ->setGlobalId($value['globalId'])
                        ->setGalleryURL($value['galleryURL'])
                        ->setViewItemURL($value['viewItemURL'])
                        ->setAutoPay($value['autoPay'])
                        ->setPostalCode($value['postalCode'])
                        ->setLocation($value['location'])
                        ->setCountry($value['country'])
                        ->setReturnsAccepted($value['returnsAccepted'])
                        ->setIsMultiVariationListing($value['isMultiVariationListing'])
                        ->setTopRatedListing($value['topRatedListing'])

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
                    $array['shippingServiceCost'] = array('currencyId' => $value['shippingInfo']['shippingServiceCost_attr']['currencyId'],
                        'value' => $value['shippingInfo']['shippingServiceCost']);
                    $array['shippingType'] = $value['shippingInfo']['shippingType'];
                    $array['shipToLocations'] = $value['shippingInfo']['shipToLocations'];
                    $array['expeditedShipping'] = $value['shippingInfo']['expeditedShipping'];
                    $array['oneDayShippingAvailable'] = $value['shippingInfo']['oneDayShippingAvailable'];
                    $array['handlingTime'] = $value['shippingInfo']['handlingTime'];
                    $item->setArrayShippingInfo($array);
                }

                if (isset($value['sellingStatus'])) {

                    $array = array();
                    $array['currentPrice'] = array('currencyId' => $value['sellingStatus']['currentPrice_attr']['currencyId'],
                        'value' => $value['sellingStatus']['currentPrice']);
                    $array['convertedCurrentPrice'] = array('currencyId' => $value['sellingStatus']['convertedCurrentPrice_attr'],
                        'value' => $value['sellingStatus']['convertedCurrentPrice']);
                    $array['sellingState'] = $value['sellingStatus']['sellingState'];
                    $item->setArraySellingStatus($array);
                }

                if (isset($value['listingInfo'])) {

                    $array = array();
                    $array['bestOfferEnabled'] = $value['listingInfo']['bestOfferEnabled'];
                    $array['buyItNowAvailable'] = $value['listingInfo']['buyItNowAvailable'];
                    $array['startTime'] = $value['listingInfo']['startTime'];
                    $array['endTime'] = $value['listingInfo']['endTime'];
                    $array['listingType'] = $value['listingInfo']['listingType'];
                    $array['gift'] = $value['listingInfo']['gift'];
                    $item->setArrayListingInfo($array);
                }

                if (isset($value['condition'])) {

                    $array = array();
                    $array['conditionId'] = $value['condition']['conditionId'];
                    $array['conditionDisplayName'] = $value['condition']['conditionDisplayName'];
                    $item->setArrayCondition($array);
                }

                $arrayCategories = array();
                
                if (isset($value['primaryCategory'])) {

                    $array = array();
                    $array['categoryId'] = $value['primaryCategory']['categoryId'];
                    $array['categoryName'] = $value['primaryCategory']['categoryName'];
                    $arrayCategories['primary'] = $array;
                }
                
                if (isset($value['secondaryCategory'])) {

                    $array = array();
                    $array['categoryId'] = $value['secondaryCategory']['categoryId'];
                    $array['categoryName'] = $value['secondaryCategory']['categoryName'];
                    $arrayCategories['secondary'] = $array;
                }
                
                $item->setArrayCategories($arrayCategories);
                
                if (isset($value['storeInfo'])) {
                    $array = array();
                    $array['storeName'] = $value['storeInfo'][0]['storeName'][0];
                    $array['storeURL'] = $value['storeInfo'][0]['storeURL'][0];
                    $item->setArrayStoreInfo($array);
                }
                
                if (isset($value['distance'])) {
                    $item->setArrayDistance(array('unit' => $value['distance_attr']['unit'],
                        'value' => $value['distance']));
                }

                $arrayItemObjects[] = $item;
            }
        }
        
        return $arrayItemObjects;
    }
}
