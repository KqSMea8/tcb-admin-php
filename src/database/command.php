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
    return new QueryCommand([], ['$eq', $val]);
  }

  public function neq($val)
  {
    return new QueryCommand([], ['$neq', $val]);
  }

  public function lt($val)
  {
    return new QueryCommand([], ['$lt', $val]);
  }

  public function lte($val)
  {
    return new QueryCommand([], ['$lte', $val]);
  }

  public function gt($val)
  {
    return new QueryCommand([], ['$gt', $val]);
  }

  public function gte($val)
  {
    return new QueryCommand([], ['$gte', $val]);
  }

  public function in($arr)
  {
    return new QueryCommand([], ['$in', $arr]);
  }

  public function nin($arr)
  {
    return new QueryCommand([], ['$nin', $arr]);
  }

  public function geoNear($val)
  {

    if (!($val->geometry instanceof Point)) {
      throw new TcbException(
        INVALID_PARAM,
        '"geometry" must be of type Point. Received type ' . get_class($val->geometry)
      );
    }
    if (isset($val->maxDistance) && !is_numeric($val->maxDistance)) {
      throw new TypeError(
        '"maxDistance" must be of type Number. Received type"' . get_class($val->maxDistance)
      );
    }
    if (isset($val->minDistance) && !is_numeric($val->minDistance)) {
      throw new TypeError(
        '"minDistance" must be of type Number. Received type' . get_class($val->minDistance)
      );
    }

    return new QueryCommand([], ['$geoNear', $val->geometry->toJSON()]);
  }

  public function geoWithin($val)
  {
    if (
      !($val->geometry instanceof MultiPolygon) &&
      !($val->geometry instanceof Polygon)
    ) {
      throw new TypeError(
        '"geometry" must be of type Polygon or MultiPolygon. Received type' . get_class($val->geometry)
      );
    }

    return new QueryCommand([], ['$geoWithin', $val->geometry->toJSON()]);
  }

  public function geoIntersects($val)
  {
    if (
      !($val->geometry instanceof Point) &&
      !($val->geometry instanceof LineString) &&
      !($val->geometry instanceof Polygon) &&
      !($val->geometry instanceof MultiPoint) &&
      !($val->geometry instanceof MultiLineString) &&
      !($val->geometry instanceof MultiPolygon)
    ) {
      throw new TypeError(
        '"geometry" must be of type Point, LineString, Polygon, MultiPoint, MultiLineString or MultiPolygon. Received type ' . get_class($val->geometry)
      );
    }

    return new QueryCommand([], ['$geoIntersects', $val->geometry->toJSON()]);
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
    return new UpdateCommand([], ['$set', $val]);
  }

  public function remove()
  {
    return new UpdateCommand([], ['$remove']);
  }

  public function inc($val)
  {
    return new UpdateCommand([], ['$inc', $val]);
  }

  public function mul($val)
  {
    return new UpdateCommand([], ['$mul', $val]);
  }

  public function push($val)
  {
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
    return new UpdateCommand([], ['$unshift', $val]);
  }
}
