<?php


namespace Omnipay\CybersourceRest\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $httpStatus)
    {
        parent::__construct($request, $data);
        $this->statusCode = $httpStatus;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        // Success if the statusCode is within the 2xx range
        return $this->getCode() >= 200 && $this->getCode() < 300;
    }

    public function getMessage()
    {
        return $this->getData();
    }

    public function getCode()
    {
        return $this->statusCode;
    }
}