<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Configuration\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Configuration;
use CodenamePHP\GoogleSecretsManager\Configuration\Sealed as SealedConfiguration;
use Neos\Utility\Arrays;

/**
 * Simple factory to create sealed configurations
 *
 * @psalm-import-type GSMSettings from Factory
 */
final readonly class Sealed implements Factory {

  public function __construct(public \CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory $secretsFactory) {}

  public function build(array $configuration, string $path = 'CodenamePHP.GoogleSecretsManager') : Configuration {
    /** @psalm-var GSMSettings $settings */
    $settings = (array) (Arrays::getValueByPath($configuration, $path) ?? []);
    $configuration = array_merge(['credentials' => '', 'project' => '', 'secrets' => [], 'enabled' => true], $settings);
    return new SealedConfiguration(
      $configuration['project'],
      $this->secretsFactory->buildCollection($configuration['secrets'], $configuration['project']),
      $configuration['credentials'],
      $configuration['enabled']
    );
  }
}