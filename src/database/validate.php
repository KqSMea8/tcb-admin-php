<?php
namespace Tcb\Validate;

require_once "src/consts/code.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";

use TCB\TcbException;
use Tcb\Util\Util;

/**
 * 校验模块
 */
class Validate
{
  /**
   * 检测地址位置的点
   *
   * @param point   - 经纬度
   * @param degree  - 数值
   */

  /**
   * 检测地址位置的点 function
   *
   * @param [Integer] $point
   * @param [Integer] $degree
   * @return boolean
   */
  public static function isGeopoint($point, $degree)
  {
    if (Util::whichType($degree) !== FieldType["Number"]) {
      throw new TcbException(INVALID_TYPE, "Geo Point must be number type");
    }

    // 位置的绝对值
    $degreeAbs = abs($degree);

    if ($point === "latitude" && $degreeAbs > 90) {
      throw new TcbException(INVALID_RANGE, "latitude should be a number ranges from -90 to 90");
    } else if ($point === "longitude" && $degreeAbs > 180) {
      throw new TcbException(INVALID_RANGE, "longitude should be a number ranges from -180 to 180");
    }

    return true;
  }


  public static function isNumber($num)
  {
    return is_int($num) || is_long($num) || is_double($num) || is_float($num);
  }


  public static function isInteger($param, $num)
  {
    if (!(is_int($num) || is_long($num))) {
      throw new TcbException(INVALID_PARAM, $param . '' . ErrorCode['IntergerError']);
    }
    return true;
  }


  /**
   * 是否为合法的排序字符
   *
   * @param [String] $direction
   * @return boolean
   */
  public static function isFieldOrder($direction)
  {
    if (!in_array($direction, OrderDirectionList)) {
      throw new TcbException(DirectionError, '排序字符不合法');
    }
    return true;
  }

  /**
   * 是否为合法的字段地址
   *
   * 只能是连续字段名+英文点号
   *
   * @param path
   */
  /**
   * 是否为合法的字段地址
   * 只能是连续字段名+英文点号

   * @param [String] $path
   * @return boolean
   */
  public static function isFieldPath($path)
  {
    if (!preg_match('/^[a-zA-Z0-9-_\.]/', $path)) {
      throw new TcbException(INVALID_FIELD_PATH, '字段地址不合法');
    }
    return true;
  }

  /**
   * 是否为合法操作符
   *
   * @param [type] $op
   * @return boolean
   */
  public static function isOperator($op)
  {
    if (!in_array($op, WhereFilterOpList)) {
      throw new TcbException(OpStrError, "操作符不合法");
    }
    return true;
  }

  /**
   * 集合名称是否正确
   *
   * 只能以数字字母开头
   * 可以包含字母数字、减号、下划线
   * 最大长度32位
   *
   * @param string $name
   * @return boolean
   */
  public static function isCollName(string $name)
  {
    if (!preg_match('/^[a-zA-Z0-9]([a-zA-Z0-9-_]){1,32}$/', $name)) {
      throw new TcbException(CollNameError, '集合名称不合法');
    }
    return true;
  }

  /**
   * DocID 格式是否正确
   *
   * @param string $docId
   * @return boolean
   */
  public static function isDocID(string $docId)
  {
    if (!preg_match('/^([a-fA-F0-9]){24}$/', $docId)) {
      throw new TcbException(DocIDError, 'DocID 格式不合法');
    }
    return true;
  }
}
