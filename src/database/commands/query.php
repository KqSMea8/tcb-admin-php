<?php

require_once "src/database/commands/logic.php";
// require_once "src/database/consts.php";

class QueryCommand extends LogicCommand
{

  // public $operator;

  function __construct($_actions, $step)
  {
    parent::__construct($_actions, $step);
  }

  // function __construct($operator, $operands = [], $fieldName) {
  //     super($operator, $operands, $fieldName);
  //     $this->operator = $operator;
  //     $this->_internalType = INTERNAL_TYPE["QUERY_COMMAND"];
  // }

  // private function _setFieldName($fieldName) {
  //     $command = new QueryCommand($this->operator, $this->operands, $fieldName);
  //     return $command;
  // }

  public function eq($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["EQ"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$eq', $val)));
  }

  public function neq($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["NEQ"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$neq', $val)));
  }

  public function gt($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["GT"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$gt', $val)));
  }

  public function gte($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["GTE"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$gte', $val)));
  }

  public function lt($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["LT"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$lt', $val)));
  }

  public function lte($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["LTE"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$lte', $val)));
  }

  public function in($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["IN"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$in', $val)));
  }

  public function nin($val)
  {
    // $command = new QueryCommand(QUERY_COMMANDS_LITERAL["NIN"], [$val], $this->fieldName);
    return $this->and(new QueryCommand(array(), array('$nin', $val)));
  }

  // public static function isQueryCommand($object)
  // {
  //     return object && (object instanceof QueryCommand) && ($object->_internalType === INTERNAL_TYPE["QUERY_COMMAND"]);
  // }

  // public static function isComparisonCommand($object)
  // {
  //     return self::isQueryCommand($object);
  // }
}
