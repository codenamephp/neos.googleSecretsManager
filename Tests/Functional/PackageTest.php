<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Functional;

use CodenamePHP\GoogleSecretsManager\ConfigurationLoader\OverrideSettings;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Tests\FunctionalTestCase;

final class PackageTest extends FunctionalTestCase {

  public function test() {
    $config = $this->objectManager->get(ConfigurationManager::class)
      ->getConfiguration(OverrideSettings::CONFIGURATION_TYPE);
    var_dump($config);
  }
}
