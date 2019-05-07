<?php
namespace Tcb\DataFormat;

function checkSpecialClass($data)
{
  if (get_class($data) === 'Point' || get_class($data) === 'LineString' || get_class($data) === 'Polygon' || get_class($data) === 'MultiPoint' || get_class($data) === 'MultiLineString' || get_class($data) === 'MultiPolygon') {
    return 'Geo';
  }
  if (get_class($data) === 'RegExp') {
    return 'regExp';
  }
  if (get_class($data) === 'ServerDate') {
    return 'serverDate';
  }
  return '';
}

function is_assoc($arr)
{
  return array_keys($arr) !== range(0, count($arr) - 1);
}

function dataFormat($data)
{
  function checkSpecial($data)
  {
    foreach ($data as $key => $item) {
      if (checkSpecialClass($item) === 'Geo') {
        $data[$key] = $item->toJSON();
      } else if (checkSpecialClass($item) === 'regExp') {
        $data[$key] = $item->parse();
      } else if (checkSpecialClass($item) === 'serverDate') {
        $data[$key] = $item->parse();
      } else if (is_array($item) && is_assoc($item)) { // todo 检查是否为关联数组
        checkSpecial($item);
      }
    }
  }
  checkSpecial($data);
  return $data;
}
