<?php

require_once "src/consts/code.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";

use TencentCloud\Common\Exception\TencentCloudSDKException;

/**
 * 校验模块
 */
class Validate {
    /**
     * 检测地址位置的点
     *
     * @param point   - 经纬度
     * @param degree  - 数值
     */

    /**
     * 检测地址位置的点 function
     *
     * @param [Integer] $point
     * @param [Integer] $degree
     * @return boolean
     */
    public static function isGeopoint($point, $degree) {
        if (Util::whichType($degree) !== FieldType["Number"]) {
            throw new TencentCloudSDKException(INVALID_TYPE, "Geo Point must be number type");
        }

        // 位置的绝对值
        $degreeAbs = abs($degree);

        if ($point === "latitude" && $degreeAbs > 90) {
            throw new TencentCloudSDKException(INVALID_RANGE, "latitude should be a number ranges from -90 to 90");
        } else if ($point === "longitude" && $degreeAbs > 180) {
            throw new TencentCloudSDKException(INVALID_RANGE, "longitude should be a number ranges from -180 to 180");
        }

        return true;
    }
}


?>