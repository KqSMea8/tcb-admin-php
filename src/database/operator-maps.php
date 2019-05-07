<?php
require_once "src/database/commands/query.php";
require_once "src/database/commands/logic.php";
require_once "src/database/commands/update.php";

$maps = [];

foreach (QUERY_COMMANDS_LITERAL as $val) {
  $maps[$val] = `$` . strtolower($val);
}

foreach (LOGIC_COMMANDS_LITERAL as $val) {
  $maps[$val] = `$` . strtolower($val);
}

foreach (UPDATE_COMMANDS_LITERAL as $val) {
  $maps[$val] = `$` . strtolower($val);
}

// some exceptions
$maps[QUERY_COMMANDS_LITERAL[NEQ]] = '$ne';
$maps[UPDATE_COMMANDS_LITERAL[REMOVE]] = '$unset';
$maps[UPDATE_COMMANDS_LITERAL[SHIFT]] = '$pop'; // same as POP
$maps[UPDATE_COMMANDS_LITERAL[UNSHIFT]] = '$push'; // same as PUSH

define('OperatorMap', $maps);

function operatorToString($operator)
{
  return isset(OperatorMap[$operator]) ? OperatorMap[$operator] : `$` . strtolower($operator);
}
