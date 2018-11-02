<?php
require_once "src/database/commands/logic.php";
require_once "src/database/consts.php";

class QueryCommmand extends LogicCommand {

    public $operator;

    function __construct($operator, $operands = [], $fieldName) {
        super($operator, $operands, $fieldName);
        $this->operator = $operator;
        $this->_internalType = INTERNAL_TYPE["QUERY_COMMAND"];
    }
    
    private function _setFieldName($fieldName) {
        $command = new QueryCommmand($this->operator, $this->operands, $fieldName);
        return $command;
    }

    public function eq($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["EQ"], [$val], $this->fieldName);
        return $this->and($command);
    }
    
    public function neq($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["NEQ"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function gt($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["GT"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function gte($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["GTE"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function lt($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["LT"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function lte($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["LTE"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function in($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["IN"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public function nin($val) {
        $command = new QueryCommmand(QUERY_COMMANDS_LITERAL["NIN"], [$val], $this->fieldName);
        return $this->and($command);
    }

    public static function isQueryCommand($object) {
        return object && (object instanceof QueryCommmand) && ($object->_internalType === INTERNAL_TYPE["QUERY_COMMAND"]);
    }

    public static function isComparisonCommand($object) {
        return self::isQueryCommand($object);
    }
}

?>