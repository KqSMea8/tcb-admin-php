<?php
namespace Tcb\RegExp;

use Tcb\TcbException\TcbException;


class RegExp
{

  private $regex;
  private $options;

  function __construct($opts = ['regexp' => '', 'options' => ''])
  {
    if (empty($opts['regexp'])) {
      throw new TcbException(INVALID_PARAM, 'regexp must be a string');
    }
    $this->regex = $opts['regexp'];
    $this->options = $opts['options'];
  }

  public function parse()
  {
    return array('$regex' => $this->regex, '$options' => $this->options);
  }
}
