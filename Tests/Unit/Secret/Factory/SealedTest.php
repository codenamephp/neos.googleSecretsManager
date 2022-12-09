<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Unit\Secret\Factory;

use CodenamePHP\GoogleSecretsManager\Secret\Factory\Sealed;
use CodenamePHP\GoogleSecretsManager\Secret\Sealed as Secret;
use PHPUnit\Framework\TestCase;

final class SealedTest extends TestCase {

  private Sealed $sut;

  protected function setUp() : void {
    parent::setUp();

    $this->sut = new Sealed();
  }

  public function testBuild() : void {
    self::assertEquals(new Secret('name', 'project', 'path', 'version'),
      $this->sut->build(['name' => 'name', 'project' => 'project', 'path' => 'path', 'version' => 'version']));
  }

  public function testBuildCollection() : void {
    self::assertEquals([
      new Secret('from string', 'default project', 'path from string', 'latest'),
      new Secret('with custom name', 'with custom project', 'path 1', 'with custom version'),
      new Secret('minimal', 'default project', 'path 2', 'latest'),
    ], $this->sut->buildCollection([
      'from string' => 'path from string',
      'with custom name' => ['name' => 'with custom name', 'project' => 'with custom project', 'path' => 'path 1', 'version' => 'with custom version'],
      'minimal' => ['path' => 'path 2'],
    ], 'default project'));
  }
}
