<?php
// require_once "src/database/consts.php";

class UpdateCommand
{

  // public $fieldName;
  // public $operator;
  // public $operands;
  // public $_internalType = INTERNAL_TYPE["UPDATE_COMMAND"];
  public $_actions = array();

  public function __construct($_actions, $step)
  {
    $this->_actions = array();
    if (gettype($_actions) === 'array' && count($_actions) > 0) {
      $this->_actions = $_actions;
    }
    if (gettype($step) === 'array' && count($step) > 0) {
      array_push($this->_actions, $step);
    }
    // $this->operator = $operator;
    // $this->operands = $operands;
    // $this->fieldName = isset($fieldName) ?  $fieldName : INTERNAL_TYPE["UNSET_FIELD_NAME"];
  }

  // private function _setFieldName($fieldName) {
  //     $command = new UpdateCommand($this->operator, $this->operands, $fieldName);
  //     return $command;
  // }

  // public static function isUpdateCommand($object) {
  //     return object && (object instanceof UpdateCommand) && ($object->_internalType === INTERNAL_TYPE["UPDATE_COMMAND"]);
  // }
}
