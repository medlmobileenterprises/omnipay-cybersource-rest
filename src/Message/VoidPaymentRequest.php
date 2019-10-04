<?php


namespace Omnipay\CybersourceRest\Message;


class VoidPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'clientReferenceInformation' => array(
                'code' => $this->getTransactionReference()
            ),
        );

        return $data;
    }

    public function getEndpoint()
    {
        return parent::getEndpoint() . $this->getResourcePath();
    }

    protected function getResourcePath()
    {
        return '/pts/v2/payments/' . $this->getTransactionId() . '/voids';
    }
}