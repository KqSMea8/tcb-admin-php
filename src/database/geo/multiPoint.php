<?php
namespace Tcb\Geo\MultiPoint;

require_once "src/database/validate.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";
require_once "src/consts/code.php";

use Tcb\TcbException;

/**
 * 地理位置
 */
class MultiPoint
{
  /**
   * 多个point
   * 
   */
  public $points = [];

  /**
   * 初始化
   *
   * @param [Integer] $longitude
   * @param [Integer] $latitude
   */
  function __construct(array $points)
  {
    if (gettype($points) !== 'array') {
      throw new TcbException(INVALID_PARAM, 'points must be of type Point. Receive type' . gettype($points));
    }

    if (count($points) === 0) {
      throw new TcbException(INVALID_PARAM, 'points must contain 1 point at least');
    }

    foreach ($points as $point) {
      if (get_class($point) !== 'Point') {
        throw new TcbException(INVALID_PARAM, 'point must be of type Point. Receive type' . gettype($points));
      }
    }

    $this->points = $points;
  }

  public function toJSON()
  {
    return array('type' => 'MultiPoint', 'coordinates' => array_map(function ($item) {
      return $item->toJSON()['coordinates'];
    }, $this->points));
  }


  public static function validate($multiPoint)
  {
    if (!isset($multiPoint['type']) || !isset($multiPoint['coordinates'])) {
      return false;
    }
    if ($multiPoint['type'] !== 'MultiPoint' || gettype($multiPoint['coordinates']) !== 'array') {
      return false;
    }

    foreach ($multiPoint['coordinates'] as $point) {
      if (!is_numeric($point[0]) || !is_numeric($point[1])) {
        return false;
      }
    }
    return true;
  }
}
