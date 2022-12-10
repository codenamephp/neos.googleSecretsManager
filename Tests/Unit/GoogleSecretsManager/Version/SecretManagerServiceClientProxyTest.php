<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Unit\GoogleSecretsManager\Version;

use CodenamePHP\GoogleSecretsManager\GoogleSecretsManager\Version\SecretManagerServiceClientProxy;
use CodenamePHP\GoogleSecretsManager\Secret\Sealed;
use PHPUnit\Framework\TestCase;

final class SecretManagerServiceClientProxyTest extends TestCase {

  private SecretManagerServiceClientProxy $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new SecretManagerServiceClientProxy();
  }

  public function testSecretVersionName() : void {
    self::assertSame('projects/path/secrets/name/versions/latest', $this->sut->secretVersionName(new Sealed('name', 'path', 'version')));
  }
}
