<?php
namespace Tcb\Geo\Point;

require_once "src/database/validate.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";

use Tcb\Validate\Validate;

/**
 * 地理位置
 */
class Point
{
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
  function __construct($longitude, $latitude)
  {
    Validate::isGeopoint("latitude", $latitude);
    Validate::isGeopoint("longitude", $longitude);

    $this->latitude = $latitude;
    $this->longitude = $longitude;
  }

  public function toJSON()
  {
    return array('type' => 'Point', 'coordinates' => array($this->longitude, $this->latitude));
  }

  public function toReadableString()
  {
    return '[' . $this->longitude . $this->latitude . ']';
  }

  public static function validate($point)
  {
    if (array_key_exists('type', $point) && $point['type'] === 'Point' && array_key_exists('coordinates', $point) && gettype($point['coordinates']) === 'array') {
      if (
        Validate::isGeopoint("latitude", $point['coordinates'][0]) &&
        Validate::isGeopoint("longitude", $point['coordinates'][1])
      ) {
        return true;
      }
    }
    return false;
  }
}
