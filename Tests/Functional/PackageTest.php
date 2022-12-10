<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Functional;

use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Tests\FunctionalTestCase;

final class PackageTest extends FunctionalTestCase {

  public function testCanSetConfigurations() : void {
    $config = $this->objectManager->get(ConfigurationManager::class)->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);

    self::assertEquals('my-simple-secret-payload', $config['some']['path']);
    self::assertEquals('my-complex-secret-payload', $config['other']['path']);
  }
}
