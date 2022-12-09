<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Configuration;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;

final readonly class Simple implements Factory {

  public function build(Configuration $configuration) : SecretManagerServiceClient {
    defined('FLOW_PATH_ROOT') || define('FLOW_PATH_ROOT', '');

    return new SecretManagerServiceClient(array_filter([
      'credentials' => str_replace('%FLOW_PATH_ROOT%', (string) FLOW_PATH_ROOT, $configuration->getCredentials()),
    ]));
  }
}