<?php

class Logger
{
  private $directory;
  private $file;

  public function __construct($fileName)
  {
    $directory = dirname(__FILE__).'/../logs/';
    if (!file_exists($directory))
    {
      mkdir($directory, 0777, true);
    }
    $this->directory = realpath($directory);
    $this->fileName = $fileName;
    touch($directory.$fileName);
  }

  public function getDirectory()
  {
    return $this->directory;
  }

  public function getFileName()
  {
    return $this->fileName;
  }

  public function write($row)
  {
    file_put_contents($this->directory.'/'.$this->fileName, $row."\n", FILE_APPEND | LOCK_EX);
  }
}
