<?php declare(strict_types=1);
/*
 *  Copyright 2022 Bastian Schwarz <bastian@codename-php.de>.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace CodenamePHP\GoogleSecretsManager\ConfigurationLoader;

use CodenamePHP\GoogleSecretsManager\Configuration\Factory\Factory as ConfigurationFactory;
use CodenamePHP\GoogleSecretsManager\Configuration\Factory\Sealed;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\Factory as ClientFactory;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\SecretManagerServiceClient;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version\SecretManagerServiceClientProxy;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version\Version;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Sealed as SecretsFactory;
use Neos\Flow\Configuration\Loader\LoaderInterface;
use Neos\Flow\Configuration\Loader\MergeLoader;
use Neos\Flow\Configuration\Loader\SettingsLoader;
use Neos\Flow\Configuration\Source\YamlSource;
use Neos\Flow\Core\ApplicationContext;
use Neos\Utility\Arrays;

/**
 * @psalm-import-type SecretConfig from Factory
 */
final readonly class OverrideSettings implements LoaderInterface {

  public const CONFIGURATION_TYPE = 'GoogleSecretsManager';

  public function __construct(
    public SettingsLoader       $settingsLoader = new SettingsLoader(new YamlSource()),
    public LoaderInterface      $loader = new MergeLoader(new YamlSource(), self::CONFIGURATION_TYPE),
    public ConfigurationFactory $configurationFactory = new Sealed(new SecretsFactory()),
    public ClientFactory        $clientFactory = new SecretManagerServiceClient(),
    public Version              $version = new SecretManagerServiceClientProxy(),
  ) {}

  public function load(array $packages, ApplicationContext $context) : array {
    $configuration = $this->configurationFactory->build($this->loader->load($packages, $context));
    $settings = $this->settingsLoader->load($packages, $context);

    if($configuration->isEnabled()) {
      $client = $this->clientFactory->build($configuration);

      foreach($configuration->getSecrets() as $secret) {
        $settings = (array) Arrays::setValueByPath($settings, $secret->getPath(),
          $client->accessSecretVersion($this->version->secretVersionName($secret))->getPayload()?->getData());
      }
    }
    return $settings;
  }

}
