<?php
require_once "src/database/constants.php";
require_once "src/database/validate.php";
require_once "src/database/geo/point.php";
require_once "src/database/serverData/index.php";
require_once "src/database/commands/logic.php";

function serializeData($val) {
    return serializeHelper($val, [$val]);
}

function serializeHelper($val, $visited) {
    if (Validate::isInternalObject($val)) {
        switch ($val->_internalType) {
            case INTERNAL_TYPE["GEO_POINT"]: {
                return json_encode($val);
            }
            case INTERNAL_TYPE["SERVER_DATE"]: {
                return [
                    "$date" => $val->offset
                ];
            }
            default: {
                try {
                    return json_encode($val);
                } 
                catch (Exception $e) {
                    return $val;
                }
            }
        }
    }
    elseif ($val instanceof DateTime) {
        return [
            '$date' => $val->getTimestamp() * 1000
        ];
    }
    elseif (is_array($val)) {
        // TODO
        return $val;
    }
    else {
        return $val;
    }
}


function deserialize() {
    // TODO
}


?>