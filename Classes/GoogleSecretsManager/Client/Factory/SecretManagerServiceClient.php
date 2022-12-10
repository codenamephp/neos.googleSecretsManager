<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Configuration;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient as GoogleClient;
use Neos\Flow\Annotations\Proxy;

#[Proxy(false)] //disable proxy as the compiled proxies really don't like to be final ... or readonly. And we don't need the proxy here anyways
final class SecretManagerServiceClient implements Factory {

  public function build(Configuration $configuration) : GoogleClient {
    defined('FLOW_PATH_ROOT') || define('FLOW_PATH_ROOT', '');

    return new GoogleClient(array_filter([
      'credentials' => str_replace('%FLOW_PATH_ROOT%', (string) FLOW_PATH_ROOT, $configuration->getCredentials()),
    ]));
  }
}
