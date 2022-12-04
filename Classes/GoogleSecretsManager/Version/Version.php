<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;

/**
 * Interface to create version strings from secrets
 */
interface Version {

  public function secretVersionName(Secret $secret) : string;
}