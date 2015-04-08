<?php

class ExceptionService
{
  public function notify($exceptionMessage)
  {
    echo "An exception has occured : " . $exceptionMessage;
  }
}
