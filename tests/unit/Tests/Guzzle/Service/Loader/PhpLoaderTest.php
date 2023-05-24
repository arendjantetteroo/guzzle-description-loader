<?php

namespace Tests\Guzzle\Service\Loader;

use Guzzle\Service\Loader\PhpLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;

class PhpLoaderTest extends TestCase
{
    protected $PhpLoader;

    protected $locator;

    public function setUp(): void
    {
        $configDirectories = array(FIXTURES_PATH);
        $this->locator = new FileLocator($configDirectories);

        $this->PhpLoader = new PhpLoader($this->locator);
    }

    public function testLoad()
    {
        $values = $this->PhpLoader->load($this->locator->locate('description.php'));

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

        $PhpLoader = new PhpLoader($locator);
        $PhpLoader->load($locator->locate('notFound.php'));
    }
}
