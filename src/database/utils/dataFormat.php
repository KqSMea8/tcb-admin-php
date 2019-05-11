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

    if ($data instanceof DateTime) {
      return 'DateTime';
    }

    return 'object';
  }

  public static function is_assoc($arr)
  {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public static function checkSpecial(&$data)
  {
    if (is_object($data)) {
      if (self::checkSpecialClass($data) === 'Geo') {
        return $data->toJSON();
      } else if (self::checkSpecialClass($data) === 'regExp') {
        return $data->parse();
      } else if (self::checkSpecialClass($data) === 'serverDate') {
        return $data->parse();
      } else if (self::checkSpecialClass($data) === 'DateTime') {
        return  [
          '$date' => $data->getTimestamp() * 1000
        ];
      }
      return $data;
    } else if (is_array($data)) {
      foreach ($data as $key => $item) {
        $data[$key] = self::checkSpecial($data[$key]);
      }
      return $data;
    }
    return $data;
  }

  public static function dataFormat($data)
  {
    $data = self::checkSpecial($data);
    return $data;
  }
}
