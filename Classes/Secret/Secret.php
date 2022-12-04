<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Secret;

/**
 * Simple interface for a secret
 */
interface Secret {

  /**
   * @return string The name of the secret in Google Secrets Manager
   */
  public function getName() : string;

  /**
   * @return string The project the secret is in in Google Secrets Manager
   */
  public function getProject() : string;

  /**
   * @return string The path in the settings that secret should replace in dot notation
   */
  public function getPath() : string;

  /**
   * @return string The version of the secret te get, should default to latest
   */
  public function getVersion() : string;
}