<?php

/*
 * @author Jordi Rejas <github@rejas.eu>
 */

namespace eBay\lib\entities;

class Item {
    
    private $itemId = 0;
    
    private $title = null;
    
    private $globalId = null;
    
    private $arrayCategories = array();
    
    private $galleryURL = null;
    
    private $viewItemURL = null;
    
    private $arrayPaymentMethod = null;
    
    private $autoPay = null;
    
    private $postalCode = null;
    
    private $location = null;
    
    private $country = null;
    
    private $arrayShippingInfo = array();
    
    private $arraySellingStatus = array();
    
    private $arrayListingInfo = array();
    
    private $returnsAccepted = null;
    
    private $arrayCondition = array();
    
    private $isMultiVariationListing = null;
    
    private $topRatedListing = null;
    
    private $charityId = null;
    
    private $compatibility = null;
    
    private $arrayDiscountPriceInfo = array();
    
    private $arrayDistance = null;
    
    private $arrayGalleryInfoContainer = array();
    
    private $galleryPlusPictureURL = null;
    
    private $pictureURLLarge = null;
    
    private $pictureURLSuperSize = null;
    
    private $productId = null;
    
    private $arraySellerInfo = array();
    
    private $arrayStoreInfo = array();
    
    private $subtitle = null;
    
    private $arrayUnitPrice = array();
    
    public function getItemId()
    {
        return $this->itemId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getGlobalId()
    {
        return $this->globalId;
    }

    public function getArrayCategories()
    {
        return $this->arrayCategories;
    }

    public function getGalleryURL()
    {
        return $this->galleryURL;
    }

    public function getViewItemURL()
    {
        return $this->viewItemURL;
    }

    public function getArrayPaymentMethod()
    {
        return $this->arraPaymentMethod;
    }

    public function getAutoPay()
    {
        return $this->autoPay;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getArrayShippingInfo()
    {
        return $this->arrayShippingInfo;
    }

    public function getArraySellingStatus()
    {
        return $this->arraySellingStatus;
    }

    public function getArrayListingInfo()
    {
        return $this->arrayListingInfo;
    }

    public function getReturnsAccepted()
    {
        return $this->returnsAccepted;
    }

    public function getArrayCondition()
    {
        return $this->arrayCondition;
    }

    public function getIsMultiVariationListing()
    {
        return $this->isMultiVariationListing;
    }

    public function getTopRatedListing()
    {
        return $this->topRatedListing;
    }

    public function getCharityId()
    {
        return $this->charityId;
    }

    public function getCompatibility()
    {
        return $this->compatibility;
    }

    public function getArrayDiscountPriceInfo()
    {
        return $this->arrayDiscountPriceInfo;
    }

    public function getArrayDistance()
    {
        return $this->arrayDistance;
    }

    public function getArrayGalleryInfoContainer()
    {
        return $this->arrayGalleryInfoContainer;
    }

    public function getGalleryPlusPictureURL()
    {
        return $this->galleryPlusPictureURL;
    }

    public function getPictureURLLarge()
    {
        return $this->pictureURLLarge;
    }

    public function getPictureURLSuperSize()
    {
        return $this->pictureURLSuperSize;
    }

    public function getProductId()
    {
        return $this->productId;
    }

    public function getArraySellerInfo()
    {
        return $this->arraySellerInfo;
    }

    public function getArrayStoreInfo()
    {
        return $this->arrayStoreInfo;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function getArrayUnitPrice()
    {
        return $this->arrayUnitPrice;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setGlobalId($globalId)
    {
        $this->globalId = $globalId;
        return $this;
    }

    public function setArrayCategories($arrayCategories)
    {
        $this->arrayCategories = $arrayCategories;
        return $this;
    }

    public function setGalleryURL($galleryURL)
    {
        $this->galleryURL = $galleryURL;
        return $this;
    }

    public function setViewItemURL($viewItemURL)
    {
        $this->viewItemURL = $viewItemURL;
        return $this;
    }

    public function setArrayPaymentMethod($arrayPaymentMethod)
    {
        $this->arrayPaymentMethod = $arrayPaymentMethod;
        return $this;
    }

    public function setAutoPay($autoPay)
    {
        $this->autoPay = $autoPay;
        return $this;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function setArrayShippingInfo($arrayShippingInfo)
    {
        $this->arrayShippingInfo = $arrayShippingInfo;
        return $this;
    }

    public function setArraySellingStatus($arraySellingStatus)
    {
        $this->arraySellingStatus = $arraySellingStatus;
        return $this;
    }

    public function setArrayListingInfo($arrayListingInfo)
    {
        $this->arrayListingInfo = $arrayListingInfo;
        return $this;
    }

    public function setReturnsAccepted($returnsAccepted)
    {
        $this->returnsAccepted = $returnsAccepted;
        return $this;
    }

    public function setArrayCondition($arrayCondition)
    {
        $this->arrayCondition = $arrayCondition;
        return $this;
    }

    public function setIsMultiVariationListing($isMultiVariationListing)
    {
        $this->isMultiVariationListing = $isMultiVariationListing;
        return $this;
    }

    public function setTopRatedListing($topRatedListing)
    {
        $this->topRatedListing = $topRatedListing;
        return $this;
    }

    public function setCharityId($charityId)
    {
        $this->charityId = $charityId;
        return $this;
    }

    public function setCompatibility($compatibility)
    {
        $this->compatibility = $compatibility;
        return $this;
    }

    public function setArrayDiscountPriceInfo($arrayDiscountPriceInfo)
    {
        $this->arrayDiscountPriceInfo = $arrayDiscountPriceInfo;
        return $this;
    }

    public function setArrayDistance($arrayDistance)
    {
        $this->arrayDistance = $arrayDistance;
        return $this;
    }

    public function setArrayGalleryInfoContainer($arrayGalleryInfoContainer)
    {
        $this->arrayGalleryInfoContainer = $arrayGalleryInfoContainer;
        return $this;
    }

    public function setGalleryPlusPictureURL($galleryPlusPictureURL)
    {
        $this->galleryPlusPictureURL = $galleryPlusPictureURL;
        return $this;
    }

    public function setPictureURLLarge($pictureURLLarge)
    {
        $this->pictureURLLarge = $pictureURLLarge;
        return $this;
    }

    public function setPictureURLSuperSize($pictureURLSuperSize)
    {
        $this->pictureURLSuperSize = $pictureURLSuperSize;
        return $this;
    }

    public function setProductId($productId)
    {
        $this->productId = $productId;
        return $this;
    }

    public function setArraySellerInfo($arraySellerInfo)
    {
        $this->arraySellerInfo = $arraySellerInfo;
        return $this;
    }

    public function setArrayStoreInfo($arrayStoreInfo)
    {
        $this->arrayStoreInfo = $arrayStoreInfo;
        return $this;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function setArrayUnitPrice($arrayUnitPrice)
    {
        $this->arrayUnitPrice = $arrayUnitPrice;
        return $this;
    }
}

