<?php


namespace Omnipay\CybersourceRest\Message;


class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $data = array(
            'clientReferenceInformation' => array(
                'code' => $this->getTransactionReference()
            ),
            'processingInformation' => array(
                'commerceIndicator' => 'internet'
            ),
            'paymentInformation' => array(
                'card' => array(
                    'number' => $this->getCard()->getNumber(),
                    'expirationMonth' => $this->getCard()->getExpiryMonth(),
                    'expirationYear' => $this->getCard()->getExpiryYear(),
                    'securityCode' => $this->getCard()->getCvv()
                )
            ),
            'orderInformation' => array(
                'amountDetails' => array(
                    'totalAmount' => $this->getAmount(),
                    'currency' => $this->getCurrency()
                ),
                'billTo' => array(
                    'firstName' => $this->getCard()->getFirstName(),
                    'lastName' => $this->getCard()->getLastName(),
                    'address1' => $this->getCard()->getAddress1(),
                    'address2' => $this->getCard()->getAddress2(),
                    'locality' => $this->getCard()->getCity(),
                    'administrativeArea' => $this->getCard()->getState(),
                    'postalCode' => $this->getCard()->getPostcode(),
                    'country' => strtoupper($this->getCard()->getCountry()),
                    'email' => strtoupper($this->getCard()->getEmail()),
                    'phoneNumber' => strtoupper($this->getCard()->getPhone()),
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
        return '/pts/v2/payments';
    }
}