<?php
// namespace Tcb\DataFormat;

use Tcb\Geo\LineString\LineString;
use Tcb\Geo\MultiLineString\MultiLineString;
use Tcb\Geo\Point\Point;
use Tcb\Geo\MultiPoint\MultiPoint;
use Tcb\Geo\Polygon\Polygon;
use Tcb\Geo\MultiPolygon\MultiPolygon;

use Tcb\ServerDate\ServerDate;
use Tcb\RegExp\RegExp;

class Format
{

  public static function checkSpecialClass($data)
  {
    if (!is_object($data)) {
      return '';
    }
    if ($data instanceof Point || $data instanceof LineString || $data instanceof Polygon || $data instanceof MultiPoint || $data instanceof MultiLineString || $data instanceof MultiPolygon) {
      return 'Geo';
    }
    if ($data instanceof RegExp) {
      return 'regExp';
    }
    if ($data instanceof ServerDate) {
      return 'serverDate';
    }

    return 'object';
  }

  public static function is_assoc($arr)
  {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public static function checkSpecial(&$data)
  {
    foreach ($data as $key => $item) {
      if (self::checkSpecialClass($item) === 'Geo') {
        $data[$key] = $item->toJSON();
      } else if (self::checkSpecialClass($item) === 'regExp') {
        $data[$key] = $item->parse();
      } else if (self::checkSpecialClass($item) === 'serverDate') {
        $data[$key] = $item->parse();
      }
      // else if (self::checkSpecialClass($item) === 'object') { } 
      else if (is_array($item)) { // todo 检查是否为数组, 关联数组与索引数组均检查
        self::checkSpecial($data[$key]);
      }
    }
  }


  public static function dataFormat($data)
  {

    self::checkSpecial($data);
    return $data;
  }
}
