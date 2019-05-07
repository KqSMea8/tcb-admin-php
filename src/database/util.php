<?php
namespace Tcb\Util;

require_once "src/database/constants.php";
require_once 'src/database/serverDate/index.php';
require_once 'src/database/geo/point.php';

require_once 'src/database/command.php';
require_once 'src/consts/code.php';

// use Tcb\Geo\Point\Point;
use Tcb\Geo\LineString\LineString;
use Tcb\Geo\MultiLineString\MultiLineString;
use Tcb\Geo\Point\Point;
use Tcb\Geo\MultiPoint\MultiPoint;
use Tcb\Geo\Polygon\Polygon;
use Tcb\Geo\MultiPolygon\MultiPolygon;

class Util
{

  /**
   * 编码为后端格式的地理位置数据
   *
   * @param [type] $point
   * @return void
   */
  // private static function encodeGeoPoint($point)
  // {
  // 	if (!($point instanceof Point)) {
  // 		throw new TencentCloudSDKException(INVALID_TYPE, "encodeGeoPoint: must be GeoPoint type");
  // 	}

  // 	$geo = new stdClass();
  // 	$geo->type = "Point";
  // 	$geo->coordinates = [$point->longitude, $point->latitude];

  // 	return $geo;
  // }

  // private static function encodeServerDate($serverDate)
  // {
  // 	return [
  // 		'$date' => [
  // 			"offset" => $serverDate->offset,
  // 		],
  // 	];
  // }

  // private static function encodeTimestamp($stamp)
  // {
  // 	if (!($stamp instanceof DateTime)) {
  // 		throw new TencentCloudSDKException(INVALID_TYPE, "encodeTimestamp: must be Date type");
  // 	}

  // 	return [
  // 		'$date' => $stamp->getTimestamp() * 1000,
  // 	];
  // }

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
          $realVal = $value->getTimestamp() * 1000; // getTimestamp是否能调
          break;
        case FieldType["Object"]:
        case FieldType["Array"]:
          $realVal = self::formatField($value);
          break;
        case FieldType["ServerDate"]:
          $realVal = new Date($value['$date']);
          break;
        default:
          $realVal = $value;
      }

      // if ($type === FieldType["GeoPoint"]) {
      // 	$realValue = [
      // 		$key => Util::encodeGeoPoint($value),
      // 	];
      // } elseif ($type === FieldType["Timestamp"]) {
      // 	$realValue = [
      // 		$key => Util::encodeTimestamp($value),
      // 	];
      // } elseif ($type === FieldType["ServerDate"]) {
      // 	$realValue = [
      // 		$key => Util::encodeServerDate($value),
      // 	];
      // } elseif ($type === FieldType["Object"]) {
      // 	if ($concatKey) {
      // 		//   $realValue = getCommandVal([[$key] => $value]);
      // 	} else {
      // 		$realValue = [
      // 			$key => Util::encodeDocumentDataForReq($value, $merge, $concatKey),
      // 		];
      // 	}
      // } elseif ($type === FieldType["Command"]) {
      // 	// $realValue = $value.parse($key);
      // } else {
      // 	$realValue = [
      // 		$key => $value,
      // 	];
      // }
      if ($arrayFlag) { // 判断document是索引数组还是关联数组
        $protoField[$key] = $realVal;
      } else {
        array_push($protoField, $realVal);
      }
    }

    return $protoField;
  }

  /**
   * 编码为后端数据格式
   *
   * @param [Array] $document
   * @param boolean $merge
   * @param boolean $concatKey
   * @return Array
   */
  // public static function encodeDocumentDataForReq($document = [], $merge = false, $concatKey = true)
  // {
  // 	$params = [];

  // 	foreach ($document as $key => $value) {
  // 		$type = Util::whichType($value);
  // 		$realVal = null;

  // 		if ($type === FieldType["GeoPoint"]) {
  // 			$realValue = [
  // 				$key => Util::encodeGeoPoint($value),
  // 			];
  // 		} elseif ($type === FieldType["Timestamp"]) {
  // 			$realValue = [
  // 				$key => Util::encodeTimestamp($value),
  // 			];
  // 		} elseif ($type === FieldType["ServerDate"]) {
  // 			$realValue = [
  // 				$key => Util::encodeServerDate($value),
  // 			];
  // 		} elseif ($type === FieldType["Object"]) {
  // 			if ($concatKey) {
  // 				//   $realValue = getCommandVal([[$key] => $value]);
  // 			} else {
  // 				$realValue = [
  // 					$key => Util::encodeDocumentDataForReq($value, $merge, $concatKey),
  // 				];
  // 			}
  // 		} elseif ($type === FieldType["Command"]) {
  // 			// $realValue = $value.parse($key);
  // 		} else {
  // 			$realValue = [
  // 				$key => $value,
  // 			];
  // 		}
  // 		$params = array_merge($params, $realValue);
  // 	}

  // 	return $params;
  // }

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
      $autoId += $chars[(rand(0, strlen($chars)))];
    }
    return $autoId;
  }
}
