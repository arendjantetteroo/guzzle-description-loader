<?php

namespace Tests\Guzzle\Service\Loader;

use Guzzle\Service\Loader\YamlLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlLoaderTest extends TestCase
{
    protected $YamlLoader;

    protected $locator;

    public function setUp(): void
    {
        $configDirectories = array(FIXTURES_PATH);
        $this->locator = new FileLocator($configDirectories);

        $this->YamlLoader = new YamlLoader($this->locator);
    }

    public function testLoad()
    {
        $values = $this->YamlLoader->load($this->locator->locate('description.yml'));

        $this->assertArrayHasKey('operations', $values);
        $this->assertArrayHasKey('models', $values);
        $this->assertArrayHasKey('certificates.add', $values['operations'], 'first level operation not found');
        $this->assertArrayHasKey('certificates.list', $values['operations'], 'import failed');
        $this->assertArrayHasKey('certificates.delete', $values['operations'], 'recursive imports failed');
    }

    public function testFileNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);

        $configDirectories = array(FIXTURES_PATH);
        $locator = new FileLocator($configDirectories);

        $YamlLoader = new YamlLoader($locator);
        $YamlLoader->load($locator->locate('notFound.yml'));
    }

    public function testInvalid()
    {
        $this->expectException(ParseException::class);

        $configDirectories = array(FIXTURES_PATH);
        $locator = new FileLocator($configDirectories);

        $YamlLoader = new YamlLoader($locator);
        $YamlLoader->load($locator->locate('invalid.yml'));
    }
}
