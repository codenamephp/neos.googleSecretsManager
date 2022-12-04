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

use CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Sealed;
use Google\Cloud\SecretManager\V1\SecretManagerServiceClient;
use Neos\Flow\Configuration\Exception\InvalidConfigurationException;
use Neos\Flow\Configuration\Loader\LoaderInterface;
use Neos\Flow\Configuration\Loader\MergeLoader;
use Neos\Flow\Configuration\Loader\SettingsLoader;
use Neos\Flow\Configuration\Source\YamlSource;
use Neos\Flow\Core\ApplicationContext;
use Neos\Utility\Arrays;

final class OverrideSettings implements LoaderInterface {

  public const CONFIGURATION_TYPE = 'GoogleSecretsManager';

  public function __construct(
    public readonly SettingsLoader $settingsLoader = new SettingsLoader(new YamlSource()),
    public readonly LoaderInterface $loader = new MergeLoader(new YamlSource(), self::CONFIGURATION_TYPE),
    public readonly Factory $secretFactory = new Sealed(),
  ) {}

  public function load(array $packages, ApplicationContext $context) : array {
    $configuration = $this->loader->load($packages, $context)['CodenamePHP']['GoogleSecretsManager'] ?? [];
    $settings = $this->settingsLoader->load($packages, $context);

    return $configuration['enabled'] ?? true ? $this->overrideWithSecrets($settings, $configuration) : $settings;
  }

  public function overrideWithSecrets(array $settings, array $gsmConfig) : array {
    $project = $gsmConfig['project'] ?? throw new InvalidConfigurationException('CodenamePHP.GoogleSecretsManager.project is missing!');

    defined('FLOW_PATH_ROOT') || define('FLOW_PATH_ROOT', dirname(__DIR__, 4));

    $client = new SecretManagerServiceClient(array_filter(['credentials' => str_replace('%FLOW_PATH_ROOT%', FLOW_PATH_ROOT, $gsmConfig['credentials'] ?? '')]));

    foreach($this->secretFactory->buildCollection($gsmConfig['secrets'] ?? [], $project) as $secretConfig) {
      $secret = $client->accessSecretVersion(SecretManagerServiceClient::secretVersionName($secretConfig->getProject(), $secretConfig->getName(), $secretConfig->getVersion()));
      $settings = (array) Arrays::setValueByPath($settings, $secretConfig->getPath(), $secret->getPayload()?->getData());
    }
    return $settings;
  }
}
