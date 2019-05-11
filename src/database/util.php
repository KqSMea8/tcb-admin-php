<?php
namespace TencentCloudBase\Database;

require_once "src/database/constants.php";

require_once 'src/consts/code.php';


use TencentCloudBase\Database\Geo\LineString;
use TencentCloudBase\Database\Geo\MultiLineString;
use TencentCloudBase\Database\Geo\MultiPoint;
use TencentCloudBase\Database\Geo\MultiPolygon;
use TencentCloudBase\Database\Geo\Point;
use TencentCloudBase\Database\Geo\Polygon;
use TencentCloudBase\Utils\TcbException;

class Util
{
  /**
   * 
   * 检查array是否为关联数组
   */
  private static function is_assoc($arr)
  {
    if (!is_array($arr)) {
      return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  /**
   *
   * 格式化字段
   *
   * 主要是递归数组和对象，把地理位置和日期时间转换为js对象。
   *
   * @param document
   * @internal
   */
  private static function formatField($document)
  {
    // $keys = array_keys($document);
    $protoField = array();
    $arrayFlag = self::is_assoc($document);

    foreach ($document as $key => $value) {
      $type = Util::whichType($value);
      $realVal = null;

      switch ($type) {
        case FieldType["GeoPoint"]:
          $realVal = new Point($value['coordinates'][0], $value['coordinates'][1]);
          break;
        case FieldType["GeoLineString"]:
          $realVal = new LineString(array_map(function ($item) {
            return new Point($item[0], $item[1]);
          }, $value['coordinates']));
          break;
        case FieldType["GeoPolygon"]:
          $realVal = new Polygon(array_map(function ($line) {
            return new LineString(array_map(function ($item) {
              return new Point($item[0], $item[1]);
            }, $line));
          }, $value['coordinates']));
          break;
        case FieldType["GeoMultiPoint"]:
          $realVal = new MultiPoint(array_map(function ($item) {
            return new Point($item[0], $item[1]);
          }, $value['coordinates']));
          break;
        case FieldType["GeoMultiLineString"]:
          $realVal = new MultiLineString(array_map(function ($line) {
            return new LineString(array_map(function ($item) {
              return new Point($item[0], $item[1]);
            }, $line));
          }, $value['coordinates']));
          break;
        case FieldType["GeoMultiPolygon"]:
          $realVal = new MultiPolygon(array_map(function ($polygon) {
            return new Polygon(array_map(function ($line) {
              return new LineString(array_map(function ($item) {
                return new Point($item[0], $item[1]); // [lng, lat]前后位置待确认
              }, $line));
            }, $polygon));
          }, $value['coordinates']));
          break;
        case FieldType["Timestamp"]:
          $realVal = $value['$timestamp'] * 1000; // getTimestamp是否能调
          break;
        case FieldType["Object"]:
        case FieldType["Array"]:
          $realVal = self::formatField($value);
          break;
        case FieldType["ServerDate"]:
          $realVal = $value['$date']; // 直接返回时间戳？
          break;
        default:
          $realVal = $value;
      }
      if ($arrayFlag) { // 判断document是索引数组还是关联数组
        $protoField[$key] = $realVal;
      } else {
        array_push($protoField, $realVal);
      }
    }

    return $protoField;
  }

  public static function formatResDocumentData($documents = [])
  {
    return array_map(function ($document) {
      return self::formatField($document);
    }, $documents);
  }

  /**
   * 查看数据类型
   *
   * @param [Array] $obj
   * @return [String]
   */
  public static function whichType($obj)
  {
    $type = gettype($obj);

    if (self::is_assoc($obj)) {
      if (isset($obj['$timestamp'])) {
        return FieldType['Timestamp'];
      } else if (isset($obj['$date'])) {
        return FieldType['ServerDate'];
      } else if (Point::validate($obj)) {
        return FieldType['GeoPoint'];
      } else if (LineString::validate($obj)) {
        return FieldType['GeoLineString'];
      } else if (Polygon::validate($obj)) {
        return FieldType['GeoPolygon'];
      } else if (MultiPoint::validate($obj)) {
        return FieldType['GeoMultiPoint'];
      } else if (MultiLineString::validate($obj)) {
        return FieldType['GeoMultiLineString'];
      } else if (MultiPolygon::validate($obj)) {
        return FieldType['GeoMultiPolygon'];
      }
    }
    // if ($obj instanceof Point) {
    // 	return FieldType['GeoPoint'];
    // } elseif ($obj instanceof DateTime) {
    // 	return FieldType['Timestamp'];
    // } elseif ($obj instanceof Command) {
    // 	return FieldType['Command'];
    // } elseif ($obj instanceof ServerDate) {
    // 	return FieldType['ServerDate'];
    // }

    if ($type === 'integer' || $type === 'double') {
      return FieldType["Number"];
    }

    return ucfirst($type);
    // return $type;
  }

  /**
   * 生成文档ID, 为创建新文档使用
   *
   * @return string
   */
  public static function generateDocId()
  {
    $chars = "ABCDEFabcdef0123456789";
    $autoId = "";
    for ($i = 0; $i < 24; $i++) {
      $index = rand(0, strlen($chars));
      $test = substr($chars, $index, 1);
      $autoId = $autoId . $test;
    }
    return $autoId;
  }
}
