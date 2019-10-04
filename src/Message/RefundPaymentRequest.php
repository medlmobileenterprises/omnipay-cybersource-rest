<?php


namespace Omnipay\CybersourceRest\Message;


class RefundPaymentRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'clientReferenceInformation' => array(
                'code' => $this->getTransactionReference()
            ),
            'orderInformation' => array(
                'amountDetails' => array(
                    'totalAmount' => $this->getAmount(),
                    'currency' => $this->getCurrency()
                ),
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
        return '/pts/v2/payments/' . $this->getTransactionId() . '/refunds';
    }
}