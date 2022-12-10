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

namespace CodenamePHP\GoogleSecretsManager;

use CodenamePHP\GoogleSecretsManager\ConfigurationLoader\OverrideSettings;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\SecretManagerServiceClient;
use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Client\Factory\TestClient;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Core\Bootstrap;

/**
 * Package bootstrap that replaces the settings loading with our wrapped version
 *
 * @psalm-suppress PropertyNotSetInConstructor This is a flow class and the properties are set by flow
 */
class Package extends \Neos\Flow\Package\Package {

  public function boot(Bootstrap $bootstrap) : void {
    $bootstrap->getSignalSlotDispatcher()
      ->connect(ConfigurationManager::class, 'configurationManagerReady', function(ConfigurationManager $configurationManager) use ($bootstrap) {
        $clientFactory = $bootstrap->getContext()->isTesting() ? new TestClient() : new SecretManagerServiceClient();
        $configurationManager->registerConfigurationType(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS,
          new OverrideSettings(clientFactory: $clientFactory)
        );
      });
  }
}
