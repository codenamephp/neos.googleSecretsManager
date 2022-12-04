<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;

final class SecretManagerServiceClientProxy implements Version {

  public function secretVersionName(Secret $secret) : string {
    return SecretManagerServiceClient::secretVersionName($secret->getProject(), $secret->getPath(), $secret->getVersion());
  }
}