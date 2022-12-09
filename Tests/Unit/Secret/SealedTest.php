<?php declare(strict_types=1);

namespace CodenamePHP\GoogleSecretsManager\Tests\Unit\Secret;

use CodenamePHP\GoogleSecretsManager\Secret\Sealed;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class SealedTest extends TestCase {

  public function test__construct() : void {
    $sealed = new Sealed('name', 'project', 'path', 'version');

    self::assertInstanceOf(Sealed::class, $sealed);
    self::assertSame('name', $sealed->getName());
    self::assertSame('project', $sealed->getProject());
    self::assertSame('path', $sealed->getPath());
    self::assertSame('version', $sealed->getVersion());
  }

  public function test__construct_withMinimalParameters() : void {
    $sealed = new Sealed('name', 'project', 'path');

    self::assertInstanceOf(Sealed::class, $sealed);
    self::assertSame('name', $sealed->getName());
    self::assertSame('project', $sealed->getProject());
    self::assertSame('path', $sealed->getPath());
    self::assertSame('latest', $sealed->getVersion());
  }

  public function test__construct_canThrowException_ifNameIsEmpty() : void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Name must not be empty');

    new Sealed('', '', '', '');
  }

  public function test__construct_canThrowException_ifProjectIsEmpty() : void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Project must not be empty');

    new Sealed('foo', '', '', '');
  }

  public function test__construct_canThrowException_ifPathIsEmpty() : void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Path must not be empty');

    new Sealed('foo', 'bar', '', '');
  }

  public function test__construct_canThrowException_ifVersionIsEmpty() : void {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Version must not be empty');

    new Sealed('foo', 'bar', 'baz', '');
  }
}
