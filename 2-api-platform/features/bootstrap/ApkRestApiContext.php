<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Psr7\MultipartStream;
use Ubirak\RestApiBehatExtension\Rest\RestApiBrowser;
use Ubirak\RestApiBehatExtension\Json\JsonInspector;

class ApkRestApiContext implements Context
{
    private $restApiBrowser;

    private $jsonInspector;

    private $uploadedFileId;

    private static $storage;

    public function __construct(RestApiBrowser $restApiBrowser, JsonInspector $jsonInspector)
    {
        $this->restApiBrowser = $restApiBrowser;
        $this->jsonInspector = $jsonInspector;
    }

    /**
     * @When /^(?:I )?send a multipart "([A-Z]+)" request to "([^"]+)" with form data:$/
     */
    public function iSendAMultipartRequestToWithFormData($method, $uri, TableNode $post)
    {
        $multipartData = [];
        foreach ($post->getColumnsHash() as $i => $columnsHash) {

            if (!isset($columnsHash['name'], $columnsHash['contents'])) {
                throw new \DomainException('Multipart requests require a `name` and `contents` Behat table node');
            }

            if ('file' === $columnsHash['name']) {
                $columnsHash['contents'] = fopen($columnsHash['contents'], 'rb');
            }

            $multipartData[] = $columnsHash;
        }

        $multipartStream = new MultipartStream($multipartData);

        $this->restApiBrowser->addRequestHeader('content-type', 'multipart/form-data; boundary='.$multipartStream->getBoundary());
        $this->restApiBrowser->sendRequest($method, $uri, $multipartStream);

        $result = $this->jsonInspector->readJson()->getRawContent();
        if (isset($result->id)) {
            $this->uploadedFileId = $result->id;
        }
    }

    /**
     * @When /^(?:I )?send request for get uploaded file$/
     */
    public function iSendRequestForGetUploadedFile()
    {
        if (null === $this->uploadedFileId) {
            throw new InvalidArgumentException();
        }

        $this->restApiBrowser->sendRequest('GET', 'files/'.$this->uploadedFileId);
    }

    /**
     * @When /^(?:I )?send request for delete uploaded file$/
     */
    public function iSendRequestForDeleteUploadedFile()
    {
        if (null === $this->uploadedFileId) {
            throw new InvalidArgumentException();
        }

        $this->restApiBrowser->sendRequest('DELETE', 'files/'.$this->uploadedFileId);
    }

    /**
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and save "([^"]+)" with path expression "([^"]+)" with body:$/
     */
    public function iSendARequestToAndSaveDataWithPathExpressionWithBody($method, $url, $key, $pathExpression, PyStringNode $body)
    {
        $this->restApiBrowser->setRequestHeader('content-type', 'application/ld+json');

        $this->restApiBrowser->sendRequest($method, $url, (string)$body);

        static::$storage[$key] = $this->jsonInspector->searchJsonPath($pathExpression);
    }

    /**
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and content-type "([^"]+)" and save "([^"]+)" with path expression "([^"]+)" with body:$/
     */
    public function iSendARequestToAndContentTypeAndSaveDataWithPathExpressionWithBody($method, $url, $contentType, $key, $pathExpression, PyStringNode $body)
    {
        $this->restApiBrowser->setRequestHeader('content-type', $contentType);

        $this->restApiBrowser->sendRequest($method, $url, (string)$body);

        static::$storage[$key] = $this->jsonInspector->searchJsonPath($pathExpression);
    }

    /**
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and content-type "([^"]+)" with body:$/
     */
    public function iSendARequestToAndContentTypeWithBody($method, $url, $contentType, PyStringNode $body)
    {
        $this->restApiBrowser->setRequestHeader('content-type', $contentType);

        $this->restApiBrowser->sendRequest($method, $url, (string)$body);
    }

    /**
     * !NB в $url должен быть placeholder %_PLACEHOLDER_%
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and using "([^"]+)"$/
     */
    public function iSendARequestToAndUsing($method, $url, $key)
    {
        if (!isset(static::$storage[$key])) {
            throw new \InvalidArgumentException();
        }

        $url = str_replace('%_PLACEHOLDER_%', static::$storage[$key], $url);
        $this->restApiBrowser->sendRequest($method, $url);
    }

    /**
     * !NB в $body должен быть placeholder %_PLACEHOLDER_%
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and using "([^"]+)" and with body:$/
     */
    public function iSendARequestToAndUsingAndWithBody($method, $url, $key, PyStringNode $body)
    {
        if (!isset(static::$storage[$key])) {
            throw new \InvalidArgumentException();
        }

        $body = str_replace('%_PLACEHOLDER_%', static::$storage[$key], (string)$body);

        $this->restApiBrowser->sendRequest($method, $url, $body);
    }

    /**
     * !NB в $body должен быть placeholder %_PLACEHOLDER_%
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" and using "([^"]+)" and save "([^"]+)" with path expression "([^"]+)" and with body:$/
     */
    public function iSendARequestToAndUsingAndSaveDataWithPathExpressionAndWithBody($method, $url, $key, $storageKey, $pathExpression, PyStringNode $body)
    {
        if (!isset(static::$storage[$key])) {
            throw new \InvalidArgumentException();
        }

        $this->restApiBrowser->setRequestHeader('content-type', 'application/ld+json');

        $body = str_replace('%_PLACEHOLDER_%', static::$storage[$key], (string)$body);

        $this->restApiBrowser->sendRequest($method, $url, $body);

        static::$storage[$storageKey] = $this->jsonInspector->searchJsonPath($pathExpression);
    }
}