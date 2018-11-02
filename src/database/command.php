<?php
require_once "src/database/constants.php";
require_once "src/database/logic.php";
require_once "src/database/query.php";
require_once "src/database/update.php";

class Command {

    /**
     * Query and Projection Operators
     * https://docs.mongodb.com/manual/reference/operator/query/
     * @param target
     */

    public function eq($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["EQ"], [$val]);
    }

    public function neq($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["NEQ"], [$val]);
    }

    public function gt($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["GT"], [$val]);
    }

    public function gte($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["GTE"], [$val]);
    }

    public function lt($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["LT"], [$val]);
    }

    public function lte($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["LTE"], [$val]);
    }

    public function in($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["IN"], [$val]);
    }

    public function nin($val) {
        return new QueryCommmand(QUERY_COMMANDS_LITERAL["NIN"], [$val]);
    }

    /**
     * Update Operators
     * https://docs.mongodb.com/manual/reference/operator/update/
     * @param target
     */

    public function mul($val) {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["MUL"], [$val]);
    }

    public function remove($val) {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["REMOVE"], []);
    }

    public function inc($val) {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["INC"], [$val]);
    }

    public function set($val) {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["SET"], [$val]);
    }

    public function push($val) {
        $values = is_array($argv[0]) ? $argv[0] : $argv;
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["PUSH"], $values);
    }

    public function pop() {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["POP"], []);
    }

    public function unshift($val) {
        $values = is_array($argv[0]) ? $argv[0] : $argv;
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["UNSHIFT"], $values);
    }

    public function shift() {
        return new UpdateCommand(UPDATE_COMMANDS_LITERAL["SHIFT"], []);
    }

    public function and() {
        $expressions = is_array($argv[0]) ? $argv[0] : $argv;
        return new LogicCommand(LOGIC_COMMANDS_LITERAL["O_AND"], $expressions);
    }

    public function or() {
        $expressions = is_array($argv[0]) ? $argv[0] : $argv;
        return new LogicCommand(LOGIC_COMMANDS_LITERAL["O_OR"], $expressions);
    }

}

?>