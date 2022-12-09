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

use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\Factory as ClientFactory;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\Simple;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version\SecretManagerServiceClientProxy;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version\Version;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Sealed;
use Neos\Flow\Configuration\Exception\InvalidConfigurationException;
use Neos\Flow\Configuration\Loader\LoaderInterface;
use Neos\Flow\Configuration\Loader\MergeLoader;
use Neos\Flow\Configuration\Loader\SettingsLoader;
use Neos\Flow\Configuration\Source\YamlSource;
use Neos\Flow\Core\ApplicationContext;
use Neos\Utility\Arrays;

/**
 * @psalm-import-type SecretConfig from Factory
 * @psalm-type gsmSettings = array{enabled?: bool, project?: string, secrets: array<string, SecretConfig|string>, ...}
 */
final readonly class OverrideSettings implements LoaderInterface {

  public const CONFIGURATION_TYPE = 'GoogleSecretsManager';

  public function __construct(
    public SettingsLoader  $settingsLoader = new SettingsLoader(new YamlSource()),
    public LoaderInterface $loader = new MergeLoader(new YamlSource(), self::CONFIGURATION_TYPE),
    public Factory         $secretFactory = new Sealed(),
    public ClientFactory   $clientFactory = new Simple(),
    public Version         $version = new SecretManagerServiceClientProxy(),
  ) {}

  public function load(array $packages, ApplicationContext $context) : array {
    /** @psalm-var gsmSettings $configuration */
    $configuration = (array) ($this->loader->load($packages, $context)['CodenamePHP']['GoogleSecretsManager'] ?? []);
    $settings = $this->settingsLoader->load($packages, $context);

    if($configuration['enabled'] ?? true) {
      /** @var string $project */
      $project = $configuration['project'] ?? throw new InvalidConfigurationException('CodenamePHP.GoogleSecretsManager.project is missing!');

      $client = $this->clientFactory->build($configuration);

      foreach($this->secretFactory->buildCollection($configuration['secrets'] ?? [], $project) as $secretConfig) {
        $secret = $client->accessSecretVersion($this->version->secretVersionName($secretConfig));
        $settings = (array) Arrays::setValueByPath($settings, $secretConfig->getPath(), $secret->getPayload()?->getData());
      }
    }
    return $settings;
  }

}
