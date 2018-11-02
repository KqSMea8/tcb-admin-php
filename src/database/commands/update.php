<?php
require_once "src/database/consts.php";

class UpdateCommand {

    public $fieldName;
    public $operator;
    public $operands;
    public $_internalType = INTERNAL_TYPE["UPDATE_COMMAND"];

    function __construct($operator, $operands = [], $fieldName) {    
        $this->operator = $operator;
        $this->operands = $operands;
        $this->fieldName = isset($fieldName) ?  $fieldName : INTERNAL_TYPE["UNSET_FIELD_NAME"];
    }

    private function _setFieldName($fieldName) {
        $command = new UpdateCommand($this->operator, $this->operands, $fieldName);
        return $command;
    }

    public static function isUpdateCommand($object) {
        return object && (object instanceof UpdateCommand) && ($object->_internalType === INTERNAL_TYPE["UPDATE_COMMAND"]);
    }

}
?>