<?php

namespace Omnipay\CybersourceRest\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveRequestHost = "api.cybersource.com";
    protected $testRequestHost = "apitest.cybersource.com";
    protected $liveEndpoint = "https://api.cybersource.com";
    protected $testEndpoint = "https://apitest.cybersource.com";

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

    public function sendData($data)
    {
        $date = date("D, d M Y G:i:s ") . "GMT";
        $requestData = json_encode($data, JSON_UNESCAPED_SLASHES);

        $headers = $this->generateSignature($this->getResourcePath(), strtolower($this->getHttpMethod()), $requestData, $date);
        $headers['Content-Type'] = 'application/json';
        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $requestData);
        return $this->response = new Response($this, $httpResponse->getBody()->getContents(), $httpResponse->getStatusCode());
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getResourcePath()
    {
        return '';
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getRequestHost()
    {
        return $this->getTestMode() ? $this->testRequestHost : $this->liveRequestHost;
    }

    function generateDigest($requestPayload)
    {
        $utf8EncodedString = utf8_encode($requestPayload);
        $digestEncode = hash("sha256", $utf8EncodedString, true);
        return base64_encode($digestEncode);
    }

    public function generateSignature($resourcePath, $httpMethod, $payload, $currentDate)
    {
        $merchant_id = $this->getMerchantId();
        $merchant_key_id = $this->getMerchantKey();
        $merchant_secret_key = $this->getMerchantSecret();
        $request_host = $this->getRequestHost();
        $digest = "";

        if ($httpMethod == "get") {
            $signatureString = "host: " . $request_host . "\ndate: " . $currentDate . "\n(request-target): " . $httpMethod . " " . $resourcePath . "\nv-c-merchant-id: " . $merchant_id;
            $headerString = "host date (request-target) v-c-merchant-id";

        } else if ($httpMethod == "post") {
            //Get digest data
            $digest = $this->generateDigest($payload);

            $signatureString = "host: " . $request_host . "\ndate: " . $currentDate . "\n(request-target): " . $httpMethod . " " . $resourcePath . "\ndigest: SHA-256=" . $digest . "\nv-c-merchant-id: " . $merchant_id;
            $headerString = "host date (request-target) digest v-c-merchant-id";
        }

        $signatureByteString = utf8_encode($signatureString);
        $decodeKey = base64_decode($merchant_secret_key);
        $signature = base64_encode(hash_hmac("sha256", $signatureByteString, $decodeKey, true));
        $signatureHeader = array(
            'keyid="' . $merchant_key_id . '"',
            'algorithm="HmacSHA256"',
            'headers="' . $headerString . '"',
            'signature="' . $signature . '"'
        );
        $signatureToken = implode(", ", $signatureHeader);

        $headers = array(
            'v-c-merchant-id' => $merchant_id,
            'Host' => $request_host,
            'Date' => $currentDate,
            'Signature' => $signatureToken
        );

        if ($httpMethod == 'post') {
            $headers['Digest'] = 'SHA-256=' . $digest;
        }

        // echo "\t" . $signatureToken . PHP_EOL;

        return $headers;
    }
}