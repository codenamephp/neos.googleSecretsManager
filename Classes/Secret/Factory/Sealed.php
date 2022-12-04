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

namespace CodenamePHP\GoogleSecretsManager\Secret\Factory;

use CodenamePHP\GoogleSecretsManager\Secret\Secret;
use Neos\Flow\Configuration\Exception\InvalidConfigurationException;

/**
 * A simple factory to create the sealed secret objects
 */
final class Sealed implements Factory {

  public function build(array $config) : Secret {
    $config += ['name' => '', 'path' => '', 'project' => '', 'version' => ''];

    if($config['name']  === '') throw new InvalidConfigurationException('Name must not be empty');
    if($config['project'] === '') throw new InvalidConfigurationException('Project must not be empty');
    if($config['path'] === '') throw new InvalidConfigurationException('Path must not be empty');
    if($config['version'] === '') throw new InvalidConfigurationException('Version must not be empty');

    return new \CodenamePHP\GoogleSecretsManager\Secret\Sealed($config['name'], $config['project'], $config['path'], $config['version']);
  }

  public function buildCollection(array $secrets, string $defaultProject) : array {
    return array_map(function(array|string $secret, string $secretName) use ($defaultProject) : Secret {
      if(is_string($secret)) {
        $secret = ['name' => $secretName, 'project' => $defaultProject, 'path' => $secret, 'version' => 'latest'];
      }else {
        $secret['name'] ??= $secretName;
        $secret['project'] ??= $defaultProject;
        $secret['version'] ??= 'latest';
      }
      return $this->build($secret);
    }, $secrets, array_keys($secrets));
  }
}
