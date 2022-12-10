<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Configuration;
use Google\Cloud\SecretManager\V1\AccessSecretVersionResponse;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient as GoogleClient;
use Google\Cloud\SecretManager\V1\SecretPayload;
use Neos\Flow\Annotations\Proxy;

/**
 * Just creates a client that returns fixed test data. This corresponds to the testing configuration
 *
 * @internal
 * @psalm-suppress all This is just a test client
 */
#[Proxy(false)] //disable proxy as the compiled proxies really don't like to be final ... or readonly. And we don't need the proxy here anyways
final class TestClient implements Factory {

  public function build(Configuration $configuration) : SecretManagerServiceClient {
    return new class() extends GoogleClient {

      public function __construct() {}

      public function accessSecretVersion($name, array $optionalArgs = []) {
        $secrets = [
          self::secretVersionName('my-project', 'my-simple-secret', 'latest') => (new SecretPayload([]))->setData('my-simple-secret-payload'),
          self::secretVersionName('my-project', 'my-complex-secret', '123') => (new SecretPayload([]))->setData('my-complex-secret-payload'),
        ];
        return (new AccessSecretVersionResponse([]))->setPayload($secrets[$name]);
      }
    };
  }
}
