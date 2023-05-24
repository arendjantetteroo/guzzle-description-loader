# Guzzle Service Description Loader

A stand-alone Service Description loader for Guzzle with compatibility for
Symfony 4.x, 5.x, and 6.x.

## Installation

If you are using Composer, and you should, just run the following command:

``` sh
composer require "inveniem/guzzle-description-loader"
```

## Supported File Formats

* Yaml
* Php
* Json

## Usage

``` php
use Guzzle\Service\Loader\JsonLoader;
use GuzzleHttp\Command\Guzzle\Description;
use Symfony\Component\Config\FileLocator;

$configDirectories = array(DESCRIPTION_PATH);
$this->locator = new FileLocator($configDirectories);

$this->jsonLoader = new JsonLoader($this->locator);

$description = $this->jsonLoader->load($this->locator->locate('description.json'));
$description = new Description($description);
```

## Sample

``` json
{
  "operations": {
    "certificates.list": {
      "httpMethod": "GET",
      "uri": "certificates",
      "description": "Lists and returns basic information about all of the management certificates associated with the specified subscription.",
      "responseModel": "CertificateList"
    }
  },
  "models": {
    "CertificateList": {
      "type": "array",
      "name": "certificates",
      "sentAs": "SubscriptionCertificate",
      "location": "xml",
      "items": {
        "type": "object"
      }
    }
  },
  "imports": [
    "description_import.json"
  ]
}
```
