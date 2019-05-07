<?php

// require_once "src/database/consts.php";

class LogicCommand
{
    // public $fieldName;
    // public $operator;
    // public $operands;
    // public $_internalType = INTERNAL_TYPE["LOGIC_COMMAND"];

    private $actions = array();


    function __construct($actions, $step)
    {
        $this->actions = array();
        if (gettype($actions) === 'array' && count($actions) > 0) {
            $this->actions = $actions;
        }
        if (gettype($step) === 'array' && count($step) > 0) {
            array_push($this->actions, $step);
        }
    }

    public function or()
    {
        $arguments = func_get_args();
        /**
         * or 操作符的参数可能是 逻辑操作对象/逻辑操作对象数组
         * _.or([_.gt(10), _.lt(100)])
         */
        if (gettype($arguments[0]) === 'array') {
            $arguments = $arguments[0];
        }
        return new LogicCommand($this->actions, array_unshift($arguments, '$or'));
    }

    public function and()
    {
        $arguments = func_get_args();
        /**
         * or 操作符的参数可能是 逻辑操作对象/逻辑操作对象数组
         * _.or([_.gt(10), _.lt(100)])
         */
        if (gettype($arguments[0]) === 'array') {
            $arguments = $arguments[0];
        }
        return new LogicCommand($this->actions, array_unshift($arguments, '$and'));
    }
    // function __construct($operator, $operands = [], $fieldName)
    // {

    //     $this->operator = $operator;
    //     $this->operands = $operands;
    //     $this->fieldName = isset($fieldName) ?  $fieldName : INTERNAL_TYPE["UNSET_FIELD_NAME"];

    //     if ($this->fieldName !== INTERNAL_TYPE["UNSET_FIELD_NAME"]) {

    //         $this->operands = $operands;
    //         $len = count($operands);

    //         for ($i = 0; $i < $len; $i++) {
    //             $query = $operands[$i];
    //             if (isLogicCommand(query) || isQueryCommand(query)) {
    //                 $operands[i] = $query->_setFieldName($this->fieldName);
    //             }
    //         }
    //     }
    // }

    // public static function isLogicCommand($object)
    // {
    //     return object && (object instanceof LogicCommand) && ($object->_internalType === INTERNAL_TYPE["LOGIC_COMMAND"]);
    // }

    // private function _setFieldName($fieldName)
    // {

    //     function loopOperands($operand)
    //     {
    //         if ($operand instanceof LogicCommand) {
    //             return $operand . _setFieldName($fieldName);
    //         } else {
    //             return $operand;
    //         }
    //     }

    //     $operands = array_map('loopOperands', $this->operands);

    //     $command = new LogicCommand($this->operator, $operands, $fieldName);
    //     return $command;
    // }

    // public function and()
    // {
    //     $expressions = is_array($argv[0]) ? $argv[0] : $argv;
    //     $expressions = array_unshift($expressions, $this);
    //     return new LogicCommand(LOGIC_COMMANDS_LITERAL[O_AND], $expressions, $this->fieldName);
    // }

    // public function or()
    // {
    //     $expressions = is_array($argv[0]) ? $argv[0] : $argv;
    //     $expressions = array_unshift($expressions, $this);
    //     return new LogicCommand(LOGIC_COMMANDS_LITERAL[O_OR], $expressions, $this->fieldName);
    // }
}
