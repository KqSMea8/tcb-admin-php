<?php
require_once 'src/consts/code.php';
require_once "src/database/constants.php";
require_once "src/database/validate.php";
require_once "src/database/geo/point.php";
require_once "src/database/serverData/index.php";
require_once "src/database/commands/logic.php";

use Tcb\TcbException;

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
        $new_val = [];
        $len = count($val);
        for ($i = 1; $i < $len; $i++) {
            if (in_array($val[$i], $visited)) {
                throw new TcbException(INVALID_PARAM, "Cannot convert circular structure to JSON");
            }

            // TODO：再验证这里的写法
            $val[$i] = serializeHelper($val[$i], [
                array_merge($val[$i], $visited)
            ]);
        }
        return $val;
    }
    else {
        return $val;
    }
}


function deserialize($val = []) {
    foreach ($val as $key => $item) {
        switch ($key) {
            case '$date': {
                if (is_int($item)) {
                    return new DateTime($item);
                }
                elseif (is_array($item)) {
                    return ServerDate($item);
                }
                break;
            }
            case 'type': {
                if ($val["type"] === "Point") {
                    if (is_array($val["coordinates"]) && Validate::isNumber($val["coordinates"][0]) && Validate::isNumber($val["coordinates"][1])) {
                        return new Point($val["coordinates"][0], $val["coordinates"][1]);
                    }
                }
                break;
          }
        }
    }
    return $val;
}


?>