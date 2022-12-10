# neos.googleSecretsManager

![Packagist Version](https://img.shields.io/packagist/v/codenamephp/neos.googleSecretsManager)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/codenamephp/neos.googleSecretsManager)
![Lines of code](https://img.shields.io/tokei/lines/github/codenamephp/neos.googleSecretsManager)
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/codenamephp/neos.googleSecretsManager)
![CI](https://github.com/codenamephp/neos.googleSecretsManager/workflows/CI/badge.svg)
![Packagist Downloads](https://img.shields.io/packagist/dt/codenamephp/neos.googleSecretsManager)
![GitHub](https://img.shields.io/github/license/codenamephp/neos.googleSecretsManager)

## Installation

Easiest way is via composer. Just run `composer require codenamephp/neos.googleSecretsManager` in your cli which should install the latest version for you.

## Usage

Just install the package. It registers itself and hooks into the loading of the settings and replaces the settings with your secrets according to your 
configuration.

## Configuration

The configuration is done via the `GoogleSecretsManager.yaml` file. The following example shows an example configuration:

```yaml
CodenamePHP:
  GoogleSecretsManager:
    credentials: '%FLOW_PATH_ROOT%/path-to-google-auth.json'
    project: 'my project'

    secrets:
      db_password:
        path: Neos.Flow.persistence.backendOptions.password
      db_user: Neos.Flow.persistence.backendOptions.user
```

All configuration is done under the `CodenamePHP.GoogleSecretsManager` key.

### Properties

#### enabled
Type: `boolean`

A boolean to switch the replacement on or off. This is useful if you want to disable the replacement in development for example.

#### credentials
Type: `string`

The `credentials` key is the path to the google auth json file or the decoded json itself. You can get this file from the google cloud console. This can also
be omitted to make the client look for the credentials in the environment variables.

Make sure the credentials have access to the secret manager AND the payload of each secret.

#### project
Type: `string`

The `project` key is the name of the project you want to access. This is the name you see in the google cloud console
and can be overwritten per secret in case some secrets are in a different project.

#### secrets
Type: `array`

The `secrets` key is an array of secrets. The key is the name of the secret in the google secrets manager and the has a long and a short form:

#### Long Form
Type: `object`

In the long form the secret is an object with the following properties:

- `path` - The path to the setting in the settings array. This can be a dot separated path to a nested setting. If the setting does not exist it will be created.
- `project (optional)` - The project to use for this secret. If not set the global project will be used.
- `name (optional)` - The name of the secret in the google secrets manager. If this is omitted the key of the secret is used as the name.
- `version (optional)` - The version of the secret to load. This is optional and defaults to `latest`. This can be used to load older versions of the secret.


#### Short Form
Type: `string`

In the short form the value is the path to the setting you want to replace. The path is a dot separated path to the setting.
