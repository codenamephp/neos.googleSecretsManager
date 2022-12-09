<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Configuration\Factory;

use CodenamePHP\GoogleSecretsManager\Configuration\Factory\Sealed;
use CodenamePHP\GoogleSecretsManager\Configuration\Sealed as Configuration;
use CodenamePHP\GoogleSecretsManager\Secret\Factory\Factory;
use CodenamePHP\GoogleSecretsManager\Secret\Secret;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SealedTest extends TestCase {

  public function testBuild() : void {
    $configuration = [
      'CodenamePHP' => [
        'GoogleSecretsManager' => [
          'project' => 'test project',
          'credentials' => 'test credentials',
          'secrets' => ['foo' => 'bar'],
          'enabled' => false,
        ],
      ],
    ];
    $secretsFactory = $this->createMock(Factory::class);
    $secretsFactory->expects(self::once())
      ->method('buildCollection')
      ->with(['foo' => 'bar'], 'test project')
      ->willReturn([$this->createMock(Secret::class)]);

    $factory = new Sealed($secretsFactory);

    self::assertEquals(new Configuration('test project', [$this->createMock(Secret::class)], 'test credentials', false), $factory->build($configuration));
  }

  public function testBuild_withMinimalData() : void {
    $configuration = [
      'CodenamePHP' => [
        'GoogleSecretsManager' => ['project' => 'test project'],
      ],
    ];
    $secretsFactory = $this->createMock(Factory::class);
    $secretsFactory->expects(self::once())->method('buildCollection')->with([], 'test project')->willReturn([]);

    $factory = new Sealed($secretsFactory);

    self::assertEquals(new Configuration('test project', [], '', true), $factory->build($configuration));
  }

  public function testBuild_withMissingConfig() : void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Project must not be empty');

    $configuration = [];

    $secretsFactory = $this->createMock(Factory::class);
    $secretsFactory->expects(self::once())->method('buildCollection')->with([], '')->willReturn([]);

    $factory = new Sealed($secretsFactory);
    $factory->build($configuration);
  }

  public function test__construct() : void {
    $secretsFactory = $this->createMock(Factory::class);

    $factory = new Sealed($secretsFactory);

    self::assertSame($secretsFactory, $factory->secretsFactory);
  }
}
