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

namespace CodenamePHP\GoogleSecretsManager\Secret;

/**
 * A simple readonly DTO for a secret
 */
final class Sealed implements Secret {

  public function __construct(public readonly string $name, public readonly string $project, public readonly string $path, public readonly string $version = 'latest') {}

  public function getName() : string {
    return $this->name;
  }

  public function getProject() : string {
    return $this->project;
  }

  public function getPath() : string {
    return $this->path;
  }

  public function getVersion() : string {
    return $this->version;
  }
}
