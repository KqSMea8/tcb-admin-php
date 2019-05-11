<?php
// namespace Tcb\Command;

require_once "src/database/constants.php";
require_once "src/database/commands/logic.php";
require_once "src/database/commands/query.php";
require_once "src/database/commands/update.php";

use Tcb\Geo\LineString\LineString;
use Tcb\Geo\MultiLineString\MultiLineString;
use Tcb\Geo\MultiPoint\MultiPoint;
use Tcb\Geo\MultiPolygon\MultiPolygon;
use Tcb\Geo\Point\Point;
use Tcb\Geo\Polygon\Polygon;
use Tcb\TcbException\TcbException;

class Command
{

  /**
   * Query and Projection Operators
   * https://docs.mongodb.com/manual/reference/operator/query/
   * @param target
   */

  public function eq($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$eq', $val]);
  }

  public function neq($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$neq', $val]);
  }

  public function lt($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$lt', $val]);
  }

  public function lte($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$lte', $val]);
  }

  public function gt($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$gt', $val]);
  }

  public function gte($val)
  {
    $val = Format::dataFormat($val);
    return new QueryCommand([], ['$gte', $val]);
  }

  public function in($arr)
  {
    $arr = Format::dataFormat($arr);
    return new QueryCommand([], ['$in', $arr]);
  }

  public function nin($arr)
  {
    $arr = Format::dataFormat($arr);
    return new QueryCommand([], ['$nin', $arr]);
  }

  public function geoNear($val)
  {
    $isObject = is_object($val);
    $isArray = is_array($val);

    if (!$isObject && !$isArray) { // 不是object 与 array类型， 直接报错
      throw new TcbException(
        INVALID_PARAM,
        '"val" must be of type array or object. Received type ' . gettype($val)
      );
    }

    $geometry = $isObject ? $val->geometry : $val['geometry'];
    $maxDistance = $isObject ? $val->maxDistance : $val['maxDistance'];
    $minDistance = $isObject ? $val->minDistance : $val['minDistance'];

    if (!($geometry instanceof Point)) {
      throw new TcbException(
        INVALID_PARAM,
        '"geometry" must be of type Point. Received type ' . gettype($geometry)
      );
    }
    if ((isset($maxDistance) && !is_numeric($maxDistance))) {
      throw new TypeError(
        '"maxDistance" must be of type Number. Received type"' . gettype($maxDistance)
      );
    }
    if ((isset($minDistance) && !is_numeric($minDistance))) {
      throw new TypeError(
        '"minDistance" must be of type Number. Received type' . gettype($minDistance)
      );
    }

    $resultGeometry = [
      'geometry' => $geometry->toJSON(),
      'maxDistance' => $maxDistance,
      'minDistance' => $minDistance
    ];

    return new QueryCommand([], ['$geoNear', $resultGeometry]);
  }

  public function geoWithin($val)
  {
    $isObject = is_object($val);
    $isArray = is_array($val);

    if (!$isObject && !$isArray) { // 不是object 与 array类型， 直接报错
      throw new TcbException(
        INVALID_PARAM,
        '"val" must be of type array or object. Received type ' . gettype($val)
      );
    }
    $geometry = $isObject ? $val->geometry : $val['geometry'];

    if (
      !($geometry instanceof MultiPolygon) &&
      !($geometry instanceof Polygon)
    ) {
      throw new TypeError(
        '"geometry" must be of type Polygon or MultiPolygon. Received type' . gettype($geometry)
      );
    }

    $resultGeometry = [
      'geometry' => $geometry->toJSON(),
    ];
    return new QueryCommand([], ['$geoWithin', $resultGeometry]);
  }

  public function geoIntersects($val)
  {
    $isObject = is_object($val);
    $isArray = is_array($val);

    if (!$isObject && !$isArray) { // 不是object 与 array类型， 直接报错
      throw new TcbException(
        INVALID_PARAM,
        '"val" must be of type array or object. Received type ' . gettype($val)
      );
    }
    $geometry = $isObject ? $val->geometry : $val['geometry'];

    if (
      !($geometry instanceof Point) &&
      !($geometry instanceof LineString) &&
      !($geometry instanceof Polygon) &&
      !($geometry instanceof MultiPoint) &&
      !($geometry instanceof MultiLineString) &&
      !($geometry instanceof MultiPolygon)
    ) {
      throw new TypeError(
        '"geometry" must be of type Point, LineString, Polygon, MultiPoint, MultiLineString or MultiPolygon. Received type ' . gettype($geometry)
      );
    }

    $resultGeometry = [
      'geometry' => $geometry->toJSON(),
    ];

    return new QueryCommand([], ['$geoIntersects', $resultGeometry]);
  }

  function  or()
  {
    $arguments = func_get_args();
    /**
     * or 操作符的参数可能是 逻辑操作对象/逻辑操作对象数组
     * _.or([_.gt(10), _.lt(100)])
     */
    if (gettype($arguments[0]) === 'array') {
      $arguments = $arguments[0];
    }
    array_unshift($arguments, '$or');
    return new LogicCommand([], $arguments);
  }

  function  and()
  {
    $arguments = func_get_args();
    /**
     * or 操作符的参数可能是 逻辑操作对象/逻辑操作对象数组
     * _.or([_.gt(10), _.lt(100)])
     */
    if (gettype($arguments[0]) === 'array') {
      $arguments = $arguments[0];
    }
    array_unshift($arguments, '$and');
    return new LogicCommand([], $arguments);
  }

  public function set($val)
  {
    $val = Format::dataFormat($val);
    return new UpdateCommand([], ['$set', $val]);
  }

  public function remove()
  {
    return new UpdateCommand([], ['$remove']);
  }

  public function inc($val)
  {
    $val = Format::dataFormat($val);
    return new UpdateCommand([], ['$inc', $val]);
  }

  public function mul($val)
  {
    $val = Format::dataFormat($val);
    return new UpdateCommand([], ['$mul', $val]);
  }

  public function push($val)
  {
    $val = Format::dataFormat($val);
    return new UpdateCommand([], ['$push', $val]);
  }

  public function pop()
  {
    return new UpdateCommand([], ['$pop']);
  }

  public function shift()
  {
    return new UpdateCommand([], ['$shift']);
  }

  public function unshift($val)
  {
    $val = Format::dataFormat($val);
    return new UpdateCommand([], ['$unshift', $val]);
  }
}
