<?php
require_once "src/database/constants.php";
require_once 'src/database/serverDate/index.php';
require_once 'src/database/geo/point.php';
require_once 'src/database/command.php';
require_once 'src/consts/code.php';


use TencentCloud\Common\Exception\TencentCloudSDKException;

class Util {
    
    /**
     * 编码为后端格式的地理位置数据
     *
     * @param [type] $point
     * @return void
     */
    private static function encodeGeoPoint($point) {
        if (!($point instanceof Point)) {
            throw new TencentCloudSDKException(INVALID_TYPE, "encodeGeoPoint: must be GeoPoint type");
        }

        $geo = new stdClass();
        $geo->type = "Point";
        $geo->coordinates = [$point->longitude, $point->latitude];
        
        return $geo;
    }

    private static function encodeServerDate($serverDate) {
        return [
            '$date' => [
                "offset" => $serverDate->offset 
            ]
        ];
    }

    private static function encodeTimestamp($stamp) {
        if (!($stamp instanceof DateTime)) {
            throw new TencentCloudSDKException(INVALID_TYPE, "encodeTimestamp: must be Date type");
        }

        return [
            '$date' => $stamp->getTimestamp() * 1000
        ];
    }

    private static function formatField($document) {

    }

    /**
     * 编码为后端数据格式
     *
     * @param [Array] $document
     * @param boolean $merge
     * @param boolean $concatKey
     * @return Array
     */
    public static function encodeDocumentDataForReq($document = [], $merge = false, $concatKey = true) {
        $params = [];

        foreach ($document as $key => $value) {
            $type = Util::whichType($value);
            $realVal = null;

            if ($type === FieldType["GeoPoint"]) {
                $realValue = [
                    $key => Util::encodeGeoPoint($value)
                ];
            }
            elseif ($type === FieldType["Timestamp"]) {
                $realValue = [
                    $key => Util::encodeTimestamp($value)
                ];
            }
            elseif ($type === FieldType["ServerDate"]) {
                $realValue = [
                    $key => Util::encodeServerDate($value)
                ];
            }
            elseif ($type === FieldType["Object"]) {
                if ($concatKey) {
                //   $realValue = getCommandVal([[$key] => $value]);
                }
                else {
                    $realValue = [
                      $key => Util::encodeDocumentDataForReq($value, $merge, $concatKey)
                    ];
                }
            }
            elseif ($type === FieldType["Command"]) {
                // $realValue = $value.parse($key);
            }
            else {
                $realValue = [
                    $key => $value 
                ];
            }
            $params = array_merge($params, $realValue);
        }

        return $params;

    }
    
    public static function formatResDocumentData($documents = []) {
        
    }

    /**
     * 查看数据类型
     *
     * @param [Array] $obj
     * @return [String]
     */
    public static function whichType($obj) {
        $type = gettype($obj);
    
        if ($obj instanceof Point) {
            return FieldType['GeoPoint'];
        }
        elseif ($obj instanceof DateTime) {
            return FieldType['Timestamp'];
        }
        elseif ($obj instanceof Command) {
            return FieldType['Command'];
        }
        elseif ($obj instanceof ServerDate) {
            return FieldType['ServerDate'];
        }

        if (isset($obj['$timestamp'])) {
            return  FieldType['Timestamp'];
        }
        else if (isset($obj['$date'])) {
            return FieldType['ServerDate'];
        }
        else if (isset($obj['coordinates']) && is_array($obj['coordinates']) && $obj['type'] === "Point") {
            return FieldType['GeoPoint'];
        }

        if ($type === 'integer' || $type === 'double') {
            return FieldType["Number"];
        }

        return ucfirst($type);
    }

    /**
     * 生成文档ID, 为创建新文档使用
     *
     * @return string
     */
    public static function generateDocId() {
        $chars = "ABCDEFabcdef0123456789";
        $autoId = "";
        for ($i = 0; $i < 24; $i++) {
            $autoId += $chars[(rand(0, strlen($chars)))];
        }
        return $autoId;
    }
    
}


?>