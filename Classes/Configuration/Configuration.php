<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Configuration;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;

/**
 * Simple interface for the configuration so we don't push around arrays all the time
 */
interface Configuration {

  /**
   * The credentials to use for the client, either as a path to a json file or as a json string. If left empty the client will try to find the credentials on
   * its own, usually by looking at the environment variables GOOGLE_APPLICATION_CREDENTIALS or GOOGLE_CLOUD_KEYFILE_JSON
   *
   * @return string
   */
  public function getCredentials() : string;

  /**
   * The name of the project in Google Secrets Manager. Must not be empty
   *
   * @return string
   */
  public function getProject() : string;

  /**
   * The secrets created from the configuration
   *
   * @return array<Secret>
   */
  public function getSecrets() : array;

  /**
   * Whether the configuration is enabled or not
   *
   * @return bool
   */
  public function isEnabled() : bool;
}