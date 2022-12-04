<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory;

use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;

final class Simple implements Factory {

  public function build(array $settings) : SecretManagerServiceClient {
    defined('FLOW_PATH_ROOT') || define('FLOW_PATH_ROOT', '');

    return new SecretManagerServiceClient(array_filter([
      'credentials' => str_replace('%FLOW_PATH_ROOT%', (string) FLOW_PATH_ROOT, $settings['credentials'] ?? ''),
    ]));
  }
}