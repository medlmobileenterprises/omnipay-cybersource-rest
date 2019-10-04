<?php


namespace Omnipay\CybersourceRest;


use Omnipay\Common\AbstractGateway;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{

    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName()
    {
        return 'Cybersource Rest';
    }

    public function getDefaultParameters()
    {
        return array(
            'testMode' => false,
            'merchant_id' => '',
            'merchant_key' => '',
            'merchant_secret' => '',
        );
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchant_id');
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchant_id', $value);
    }

    public function getMerchantKey()
    {
        return $this->getParameter('merchant_key');
    }

    public function setMerchantKey($value)
    {
        return $this->setParameter('merchant_key', $value);
    }

    public function getMerchantSecret()
    {
        return $this->getParameter('merchant_secret');
    }

    public function setMerchantSecret($value)
    {
        return $this->setParameter('merchant_secret', $value);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\AuthorizeRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\CaptureRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        switch($parameters['voidType']) {
            case 'payment':
                return $this->voidPayment($parameters);
                break;
            case 'capture':
                return $this->voidCapture($parameters);
                break;
            case 'refund':
                return $this->voidRefund($parameters);
                break;
            case 'credit':
                // Unsupported for now
            default:
                break;
        }
    }

    private function voidPayment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\VoidPaymentRequest', $parameters);
    }

    private function voidCapture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\VoidCaptureRequest', $parameters);
    }

    private function voidRefund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\VoidRefundRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        switch($parameters['refundType']) {
            case 'payment':
                return $this->refundPayment($parameters);
                break;
            case 'capture':
                return $this->refundCapture($parameters);
                break;
            default:
                break;
        }
    }

    private function refundPayment(array $parameters = array()) {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\RefundPaymentRequest', $parameters);
    }

    private function refundCapture(array $parameters = array()) {
        return $this->createRequest('\Omnipay\CybersourceRest\Message\RefundCaptureRequest', $parameters);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface purchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }
}