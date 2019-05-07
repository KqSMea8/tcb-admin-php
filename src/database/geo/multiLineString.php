<?php
namespace Tcb\Geo\MultiLineString;

require_once "src/database/validate.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";
require_once "src/consts/code.php";

use Tcb\TcbException;

/**
 * 地理位置
 */
class MultiLineString
{
    /**
     * 多个point
     * 
     */
    public $lines = [];

    /**
     * 初始化
     *
     * @param [Integer] $longitude
     * @param [Integer] $latitude
     */
    function __construct(array $lines)
    {
        if (gettype($lines) !== 'array') {
            throw new TcbException(INVALID_PARAM, '"lines" must be of type LineString[]. Received type' . gettype($lines));
        }

        if (count($lines) === 0) {
            throw new TcbException(INVALID_PARAM, 'Polygon must contain 1 linestring at least');
        }

        foreach ($lines as $line) {
            if (get_class($line) !== 'LineString') {
                throw new TcbException(INVALID_PARAM, '"lines" must be of type LineString[]. Received type' . gettype($line));
            }
        }

        $this->lines = $lines;
    }

    public function toJSON()
    {
        return array('type' => 'MultiLineString', 'coordinates' => array_map(function ($item) {
            return array_map(function ($item) {
                return array($item['longitude'], $item['latitude']);
            }, $item->points);
        }, $this->lines));
    }


    public static function validate($multiLineString)
    {
        if ((array_key_exists('type', $multiLineString) && $multiLineString['type'] !== 'MultiLineString') || (array_key_exists('coordinates', $multiLineString) && gettype($multiLineString['coordinates']) !== 'array')) {
            return false;
        }

        foreach ($multiLineString['coordinates'] as $line) {
            foreach ($line as $point) {
                if (!is_numeric($point[0]) || !is_numeric($point[1])) {
                    return false;
                }
            }
        }
        return true;
    }
}
