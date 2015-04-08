<?php

class LoggerTest extends PHPUnit_Framework_TestCase
{
  protected $logger;

  protected function setUp()
  {
    $this->logger = new Logger('test.log');
  }

  protected function tearDown()
  {
      unlink($this->logger->getDirectory().'/'.$this->logger->getFilename());
  }

  public function testGetDirectory()
  {
    $reflector = new ReflectionClass("Logger");
    $directory = realpath(dirname($reflector->getFileName()).'/../logs/');
    $this->assertEquals($directory, $this->logger->getDirectory());
  }

  public function testGetFileName()
  {
    $fileName = 'filename.log';
    $logger = new Logger($fileName);
    $this->assertEquals($fileName, $logger->getFilename());
  }

  public function testDirectoryExists()
  {
    $this->assertEquals(true, file_exists($this->logger->getDirectory()));
  }

  public function testFileExists()
  {
    $this->assertEquals(true, file_exists($this->logger->getDirectory().'/'.$this->logger->getFilename()));
  }

  public function testWriteOnce()
  {
    $row = "row";
    $this->logger->write($row);
    $this->assertEquals($row."\n", file_get_contents($this->logger->getDirectory().'/'.$this->logger->getFilename()));
  }

  public function testWriteTwice()
  {
    $row = "row";
    $this->logger->write($row);
    $this->logger->write($row);
    $expected = $row."\n".$row."\n";
    $this->assertEquals($expected, file_get_contents($this->logger->getDirectory().'/'.$this->logger->getFilename()));
  }

}
