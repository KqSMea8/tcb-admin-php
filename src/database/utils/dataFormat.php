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
    if ($data instanceof Point || $data instanceof LineString || get_class($data) instanceof Polygon || get_class($data) instanceof MultiPoint || get_class($data) instanceof MultiLineString || get_class($data) instanceof MultiPolygon) {
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
      } else if (self::checkSpecialClass($item) === 'object') { } else if (is_array($item) && self::is_assoc($item)) { // todo 检查是否为关联数组
        self::checkSpecial($item);
      }
    }
  }


  public static function dataFormat($data)
  {

    self::checkSpecial($data);
    return $data;
  }
}
