<?php 
// namespace Tcb\Geo\Point;

require_once "src/database/validate.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";

/**
 * 地理位置
 */
class Point {
    /**
     * 纬度
     * [-90, 90]
     */
    public $latitude;

    /**
     * 经度
     * [-180, 180]
     */
    public $longitude;

    /**
     * 初始化
     *
     * @param [Integer] $longitude
     * @param [Integer] $latitude
     */
    function __construct($longitude, $latitude) {
        Validate::isGeopoint("latitude", $latitude);
        Validate::isGeopoint("longitude", $longitude);

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}

?>