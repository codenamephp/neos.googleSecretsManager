<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Configuration;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;
use InvalidArgumentException;
use Neos\Flow\Annotations\Proxy;

/**
 * Simple readonly value object for the configuration
 */
#[Proxy(false)] //disable proxy as the compiled proxies really don't like to be final ... or readonly. And we don't need the proxy here anyways
final readonly class Sealed implements Configuration {

  /**
   * @param string $project The name of the project in Google Secrets Manager
   * @param array<Secret> $secrets The secrets created from the configuration
   * @param string $credentials The path to the credentials json or the credentials json itself or empty to let the client library figure it out
   * @param bool $enabled Whether the configuration is enabled or not
   */
  public function __construct(
    public string $project,
    public array  $secrets = [],
    public string $credentials = '',
    public bool   $enabled = true,
  ) {
    match (true) {
      $this->enabled && $this->project === '' => throw new InvalidArgumentException('Project must not be empty'),
      default => null,
    };
  }

  public function getCredentials() : string {
    return $this->credentials;
  }

  public function getProject() : string {
    return $this->project;
  }

  public function getSecrets() : array {
    return $this->secrets;
  }

  public function isEnabled() : bool {
    return $this->enabled;
  }
}
