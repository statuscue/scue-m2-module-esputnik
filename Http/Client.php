<?php

namespace Scue\ESputnik\Http;

use Magento\Framework\HTTP\Client\Curl;
use Scue\ESputnik\Api\Http\ClientInterface;
use Scue\ESputnik\Helper\Config;
use Psr\Log\LoggerInterface;
use Throwable;

class Client extends Curl implements ClientInterface
{
    protected $_host = 'https://esputnik.com';

    protected $successfulFlag = false;

    public function __construct(
        private Config $config,
        private LoggerInterface $logger,
        $sslVersion = 6
    ) {
        $this->setOption(CURLOPT_USERPWD, sprintf('%s:%s', $config->getLogin(), $config->getPassword()));
        $this->addHeader('Accept', 'application/json');
        $this->addHeader('Content-Type', 'application/json');

        parent::__construct($sslVersion);
    }

    protected function makeRequest($method, $uri, $params = [])
    {
        $uri = trim($uri, '/');
        $uri = sprintf('%s/%s', $this->_host, $uri);

        try {
            parent::makeRequest($method, $uri, $params);

            $response = $this->getBody();
            @json_decode($response);

            if (json_last_error() != JSON_ERROR_NONE) {
                $response = json_encode(['message' => $response], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
            } else {
                $this->successfulFlag = true;
            }
        } catch (Throwable $throwable) {
            $response = json_encode(['message' => $throwable->getMessage()]);
        }

        $this->_responseBody = $response;

        if ($this->config->getGeneralConfig('debug_enabled')) {
            $this->logger->debug('API', [
                'uri' => $uri,
                'method' => $method,
                'params' => $params,
                'headers' => $this->_headers,
                'options' => $this->_curlUserOptions,
                'response' => $response
            ]);
        }

        if (isset($throwable)) {
            throw $throwable;
        }

        return $response;
    }

    public function isSuccessful(): bool
    {
        return $this->successfulFlag;
    }
}