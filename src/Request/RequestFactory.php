<?php

namespace MobilniPlatby\Request;

use MobilniPlatby\RequestException;
use Nette\Diagnostics\Debugger;
use Nette\Http\Request as NetteRequest;

class RequestFactory
{

	/** @var  NetteRequest */
	private $request;

	/**
	 * @param NetteRequest $request
	 */
	function __construct(NetteRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * Returns Request or ConfirmRequest according to input parameters
	 *
	 * @return Request|ConfirmRequest
	 */
	public function create()
	{
		if ($this->request->getQuery('request')) {
			return $this->createConfirmRequest();
		} else {
			return $this->createRequest();
		}
	}

	/**
	 * @return Request
	 */
	private function createRequest()
	{
		// Validate request params
		$args = $this->request->getQuery();
		$keys = array('id', 'phone', 'shortcode', 'sms', 'timestamp', 'operator', 'country', 'att');
		foreach ($keys as $key) {
			if (!array_key_exists($key, $args)) {
				throw new RequestException("Key '$key' missing in request parameters.");
			}
		}

		// Assign to variables
		$id = $this->request->getQuery('id');
		$phone = $this->request->getQuery('phone');
		$shortcode = $this->request->getQuery('shortcode');
		$text = $this->request->getQuery('sms');
		$timestamp = $this->request->getQuery('timestamp');
		$operator = $this->request->getQuery('operator');
		$country = $this->request->getQuery('country');
		$att = $this->request->getQuery('att');

		// Create request
		$request = new Request($id, $phone, $shortcode, $text, $timestamp, $operator, $country, $att);
		return $request;
	}

	/**
	 * @return ConfirmRequest
	 */
	private function createConfirmRequest()
	{
		// Validate request params
		$args = $this->request->getQuery();
		$keys = array('id', 'request', 'message', 'status', 'timestamp', 'att');
		foreach ($keys as $key) {
			if (!in_array($key, $args)) {
				throw new RequestException("Key '$key' missing in request parameters.");
			}
		}

		// Assign to variables
		$id = $this->request->getQuery('id');
		$request = $this->request->getQuery('request');
		$message = $this->request->getQuery('message');
		$status = $this->request->getQuery('status');
		$timestamp = $this->request->getQuery('timestamp');
		$att = $this->request->getQuery('att');

		// Create request
		$request = new ConfirmRequest($id, $request, $message, $status, $timestamp, $att);
		return $request;
	}

}