<?php

class Command {
    
    public $logicParam;
    private $placeholder = "{{{AAA}}}";
      
    function __construct($logicParam) {
        if ($logicParam) {
            $this->logicParam = $logicParam;
        }
    }

    /**
     * Query and Projection Operators
     * https://docs.mongodb.com/manual/reference/operator/query/
     * @param target
     */

    public function eq($target) {
        return new Command($this->baseOperate('$eq', $target));
    }

    public function neq($target) {
        return new Command($this->baseOperate('$ne', $target));
    }

    public function gt($target) {
        return new Command($this->baseOperate('$gt', $target));
    }

    public function gte($target) {
        return new Command($this->baseOperate('$gte', $target));
    }

    public function lt($target) {
        return new Command($this->baseOperate('$lt', $target));
    }

    public function lte($target) {
        return new Command($this->baseOperate('$lte', $target));
    }

    public function in($target) {
        return new Command($this->baseOperate('$in', $target));
    }

    public function nin($target) {
        return new Command($this->baseOperate('$nin', $target));
    }

    /**
     * Update Operators
     * https://docs.mongodb.com/manual/reference/operator/update/
     * @param target
     */

    public function mul($target) {
        return new Command(['$mul' => [[$this->placeholder] => $target]]);
    }

    public function remove($target) {
        return new Command(['$unset' => [[$this->placeholder] => ""]]);
    }

    public function inc($target) {
        return new Command(['$inc' => [[$this->placeholder] => $target]]);
    }

    public function set($target) {
        return new Command(['$set' => [[$this->placeholder] => $target]]);
    }

    public function push($target) {
        $value = $target;
        if (is_array($target)) {
            $value = ['$each' => $target ];
        }
    
        return new Command(['$push' => [[$this->placeholder] => $value]]);
    }

    public function pop() {
        return new Command(['$pop' => [[$this->placeholder] => 1 ]]);
    }

    public function unshift($target) {
        $value = ['$each' => [$target], '$position' => 0];

        if (is_array($target)) {
            $value = ['$each' => $target, '$position' => 0];
        }
    
        return new Command(['$push' => [[$this->placeholder] => $value]]);
    }

    public function shift() {
        return new Command(['$pop' => [[$this->placeholder] => -1 ]]);
    }

    private function baseOperate($operator, $target) {
        return [
          [$this->placeholder] => [[$operator] => $target]
        ];
    }

    public function and() {
        $targets = [];

        if (count($argv) === 1 && is_array($argv[0])) {
          $targets = $argv[0];
        }
        else {
            $targets = $argv;
        }

        return new Command($this->connectOperate('$and', $targets));
    }

    public function or() {
        $targets = [];

        if (count($argv) === 1 && is_array($argv[0])) {
            $targets = $argv[0];
        }
        else {
            $targets = $argv;
        }

        return new Command($this->connectOperate('$or', $targets));
    }

    private function connectOperate($operator, $targets) {
        $logicParams = [];

        if (count($this->logicParam)) {
            array_push($logicParams, $this->logicParam);
        }

        foreach ($targets as $target) {
            if ($target instanceof Command) {
                if (count($target->logicParam) === 0) {
                    continue;
                }
                $logicParams.push($target->logicParam);
            }
        }
    }

    public function parse($key) {
        return json_decode(
            preg_replace('/{{{AAA}}}/g', json_encode($this->logicParam), $key)
        );
    }

    public function toString() {
        return $this->logicParam[0];
    }

}

?>