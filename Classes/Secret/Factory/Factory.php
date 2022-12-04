<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Secret\Factory;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;
use Neos\Flow\Configuration\Exception\InvalidConfigurationException;

/**
 * A simple factory to create the secret objects
 */
interface Factory {

  /**
   * Builds a secret object from a secret array config
   *
   * @param array{path: string, name?: string, project?: string, version?: string} $config A config for a single secret
   * @return Secret
   */
  public function build(array $config) : Secret;

  /**
   * Creates a collection of secrets from the given assoc array of secrets, most likely from the settings
   *
   * @param array<string, array{path: string, name?: string, project?: string, version?: string}|string> $secrets
   * @param string $defaultProject The default project to use if none is given in the secret config
   * @return array<Secret>
   * @throws InvalidConfigurationException
   */
  public function buildCollection(array $secrets, string $defaultProject) : array;
}