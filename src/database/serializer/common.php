<?php
require_once "src/database/validate.php";
require_once "src/database/serializer/datatype.php";
require_once "src/database/commands/logic.php";

function flatten($query, $shouldPreserverObject, $parents = [], $visited = []) {

}

function isConversionRequired($val) {
    return Validate::isInternalObject($val) || $val instanceof DateTime;
}

function notConversionRequired($val) {
    return false;
}

function flattenQueryObject($query) {
    return flatten($query, isConversionRequired, [], [$query]);
}

function flattenObject($object) {
    return flatten($object, notConversionRequired, [], [$object]);
}

function mergeConditionAfterEncode($query, $condition, $key) {

}

function encodeInternalDataType($val) {
    return serializeInternalDataType($val);
}

function decodeInternalDataType($val) {
    return deserializeInternalDataType($val);
}

?>