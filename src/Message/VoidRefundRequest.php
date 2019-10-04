<?php


namespace Omnipay\CybersourceRest\Message;


class VoidRefundRequest extends AbstractRequest
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
        return '/pts/v2/refunds/' . $this->getTransactionId() . '/voids';
    }
}