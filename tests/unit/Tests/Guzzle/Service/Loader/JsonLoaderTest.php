<?php

namespace Tests\Guzzle\Service\Loader;

use Guzzle\Service\Loader\JsonLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;

class JsonLoaderTest extends TestCase
{
    protected $jsonLoader;

    protected $locator;

    public function setUp(): void
    {
        $configDirectories = array(FIXTURES_PATH);
        $this->locator = new FileLocator($configDirectories);

        $this->jsonLoader = new JsonLoader($this->locator);
    }

    public function testLoad()
    {
        $values = $this->jsonLoader->load($this->locator->locate('description.json'));

        $this->assertArrayHasKey('operations', $values);
        $this->assertArrayHasKey('models', $values);
        $this->assertArrayHasKey('certificates.list', $values['operations'], 'first level operation not found');
        $this->assertArrayHasKey('certificates.add', $values['operations'], 'import failed');
        $this->assertArrayHasKey('certificates.get', $values['operations'], 'recursive imports failed');
    }

    public function testFileNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configDirectories = array(FIXTURES_PATH);
        $locator = new FileLocator($configDirectories);

        $jsonLoader = new JsonLoader($locator);
        $jsonLoader->load($locator->locate('notFound.json'));
    }

    public function testInvalid()
    {
        $this->expectException(\Exception::class);

        $configDirectories = array(FIXTURES_PATH);
        $locator = new FileLocator($configDirectories);

        $JsonLoader = new JsonLoader($locator);
        $JsonLoader->load($locator->locate('invalid.json'));
    }
}
