<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory;

use Google\ApiCore\ValidationException;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;

/**
 * Simple factory to create the client objects
 */
interface Factory {

  /**
   * @param array{credentials?: string, ...} $settings The settings loaded by neos
   * @return SecretManagerServiceClient
   * @throws ValidationException
   */
  public function build(array $settings) : SecretManagerServiceClient;
}