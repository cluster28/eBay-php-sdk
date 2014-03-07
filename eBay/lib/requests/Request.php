<?php

namespace eBay\lib\requests;

use \eBay\lib\responses\FindingJsonResponse;
use \eBay\lib\responses\FindingXmlResponse;

abstract class Request
{
	/*
	 * eBay app ID
	 */

	private $appId = null;

	/*
	 * Post data to send in post method
	 */
	private $postData = null;
	private $request = null;
	private $url = null;
	private $headers = array();
	private $requestProtocol = null;
	private $requestFormat = null;
	private $responseFormat = null;
	private $acceptedProtocols = array("get", "post");
	private $acceptedRequestFormats = array("xml", "json");
	private $acceptedResponseFormats = array("xml", "json");
	private $globalId = null;
	private $objConfig = null;
	private $arrayAffiliate = array();
	private $acceptedAffiliateFields = array("customId",
		"geoTargeting",
		"networkId",
		"trackingId");

	protected function __construct($appId, $sandbox = false)
	{
		$this->appId = $appId;
		$this->objConfig = json_decode(file_get_contents(dirname(dirname(dirname(__FILE__))) . '/config/config.json'));
		$calledClass = get_called_class();
		$this->url = ($sandbox) ? $this->objConfig->{$calledClass}->urls->sandbox : $this->objConfig->{$calledClass}->urls->production;
		$this->setRequestFormat($this->objConfig->default_request_format);
		$this->setResponseFormat($this->objConfig->default_response_format);
		$this->setRequestProtocol($this->objConfig->default_request_protocol);

		if (!empty($this->objConfig->default_global_id) && method_exists(get_called_class(), 'setGlobalId')) {
			$this->setGlobalId($this->objConfig->default_global_id);
		}
	}

	public function addAffiliateField($affiliateField, $value)
	{
		if (!in_array($affiliateField, $this->acceptedAffiliateFields)) {
			throw new \Exception("Affiliate field not allowed");
		}

		$this->arrayAffiliate[$affiliateField] = $value;
		return $this;
	}

	public function setRequestProtocol($requestProtocol)
	{
		if (!in_array($requestProtocol, $this->acceptedProtocols)) {
			throw new \Exception("Protocol not allowed");
		}

		$this->requestProtocol = $requestProtocol;
		return $this;
	}

	public function setRequestFormat($requestFormat)
	{
		if (!in_array($requestFormat, $this->acceptedRequestFormats)) {
			throw new \Exception("Request format not allowed");
		}
		$this->requestFormat = $requestFormat;
		return $this;
	}

	public function setResponseFormat($responseFormat)
	{
		if (!in_array($responseFormat, $this->acceptedResponseFormats)) {
			throw new \Exception("Response format not allowed");
		}
		$this->responseFormat = $responseFormat;
		return $this;
	}

	protected function _setGlobalId($globalId)
	{
		$this->globalId = $globalId;
	}

	private function _sendHttp()
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $this->url);

		if ($this->requestProtocol == 'post') {
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
		}

		if ($this->requestProtocol == 'post') {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}

		$response = curl_exec($ch);
		$arrayInfo = curl_getinfo($ch);
		curl_close($ch);

		if ($arrayInfo['http_code'] != 200) {
			throw new \Exception("Error during the request", $arrayInfo['http_code']);
		}

		$responseClass = basename(get_class($this)) . ucfirst($this->responseFormat) . 'Response';
		if ($responseClass == 'FindingJsonResponse') {
			return new FindingJsonResponse($response, $arrayInfo);
		} elseif ($responseClass == 'FindingXmlResponse') {
			return new FindingXmlResponse($response, $arrayInfo);
		}
	}

	protected function _send()
	{
		return $this->_sendHttp();
	}

	protected function _setPostData($postData)
	{
		$this->postData = $postData;
	}

	protected function _setRequest($request)
	{
		$this->request = $request;
	}

	protected function _setHeaders($headers)
	{
		$this->headers = $headers;
	}

	protected function _setUrl($url)
	{
		$this->url = $url;
	}

	public function getArrayAffiliate()
	{
		return $this->arrayAffiliate;
	}

	public function getRequestFormat()
	{
		return $this->requestFormat;
	}

	public function getResponseFormat()
	{
		return $this->responseFormat;
	}

	public function getRequestProtocol()
	{
		return $this->requestProtocol;
	}

	public function getAppId()
	{
		return $this->appId;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getGlobalId()
	{
		return $this->globalId;
	}

	public function clearGlobalId()
	{
		$this->globalId = null;
	}

	protected function _generateParamenters($array)
	{

		$parameters = '';

		if (count($array) > 0) {

			foreach ($array as $name => $value) {
				$parameters .= '&' . str_replace(" ", "%20", $name) . '=' . str_replace(" ", "%20", $value);
			}
		}

		return $parameters;
	}

	public function reset()
	{

		$class = get_class($this);
		return new $class($this->appId);
	}

	protected function _createXml($arrayData, $requestName, $xmlns)
	{
		$xmlWriter = new \XmlWriter();
		$xmlWriter->openMemory();
		$xmlWriter->startDocument('1.0', 'UTF-8');
		$xmlWriter->startElement($requestName . 'Request');
		$xmlWriter->writeAttribute('xmlns', $xmlns);
		$this->_write($xmlWriter, $arrayData);
		$xmlWriter->endElement();
		return $xmlWriter->outputMemory(true);
	}

	protected function _write(\XMLWriter $XmlWriter, $arrayData)
	{
		foreach ($arrayData as $key => $value) {

			if (is_array($value)) {
				if (!is_int($key)) {
					$XmlWriter->startElement($key);
				}

				$this->_write($XmlWriter, $value);
				if (!is_int($key)) {
					$XmlWriter->endElement();
				}
				continue;
			}

			$XmlWriter->writeElement($key, $value);
		}
	}

	protected function _addProductIdToXml($xml, $productIdType = null, $productId = null)
	{
		if ($productId === null) {
			return $xml;
		}

		$doc = new \SimpleXMLElement($xml);
		$productIdElement = $doc->addChild('productId', $productId);
		$productIdElement->addAttribute('type', $productIdType);
		return $doc->asXML();
	}

}
