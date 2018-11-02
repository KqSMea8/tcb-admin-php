<?php
require_once "src/database/commands/query.php";
require_once "src/database/commands/logic.php";
require_once "src/database/commands/operator-map.php";
require_once "src/database/serializer/common.php";

class QueryEncoder {

    public function encodeQuery($query) {
        if (isConversionRequired($query)) {
            if (LogicCommand::isLogicCommand($query)) {
                return $this->encodeLogicCommand($query);
            }
            elseif (QueryCommmand::isQueryCommand($query)) {
                return $this->encodeQueryCommand($query);
            }
            else {
                return $this->encodeQueryObject($query);
            }
        }
        else {
            if (is_array($query)) {
              return $this->encodeQueryObject($query);
            }
            else {
              // abnormal case, should not enter this block
              return $query;
            }
        }  
    }

    public function encodeLogicCommand($query) {

    }

    public function encodeQueryCommand($query) {

    }

    public function encodeComparisonCommand($query) {

    }

    public function encodeQueryObject($query) {

    }

    public function mergeConditionAfterEncode($query, $condition, $key) {

    }

}

class QuerySerializer {

    public static function encode($query) {
        $encoder = new QueryEncoder();
        return $encoder.encodeQuery($query);
    }
}

?>