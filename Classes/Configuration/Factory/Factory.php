<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Configuration\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Configuration;

/**
 * Factory to create the configuration from the settings
 *
 * @psalm-import-type SecretConfig from \CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory
 * @psalm-type GSMSettings = array{enabled ?: bool, project ?: string, secrets: array<string, SecretConfig|string>, ...}
 */
interface Factory {

  /**
   * Builds a configuration object from the given settings
   *
   * @param array $configuration The settings loaded from yaml
   * @param string $path
   * @return Configuration
   */
  public function build(array $configuration, string $path = 'CodenamePHP.GoogleSecretsManager') : Configuration;
}