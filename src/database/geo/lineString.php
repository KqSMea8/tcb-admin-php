<?php
namespace Tcb\Geo\LineString;

require_once "src/database/validate.php";
require_once "src/database/constants.php";
require_once "src/database/util.php";
require_once "src/consts/code.php";

use Tcb\TcbException;

/**
 * 地理位置
 */
class LineString
{
    /**
     * 多个point
     * 
     */
    public $points = [];

    /**
     * 初始化
     *
     * @param [Integer] $longitude
     * @param [Integer] $latitude
     */
    function __construct(array $points)
    {
        if (gettype($points) !== 'array') {
            throw new TcbException(INVALID_PARAM, 'points must be of type Point. Receive type' . gettype($points));
        }

        if (count($points) < 2) {
            throw new TcbException(INVALID_PARAM, '"points" must contain 2 points at least');
        }

        foreach ($points as $point) {
            if (get_class($point) !== 'Point') {
                throw new TcbException(INVALID_PARAM, 'point must be of type Point. Receive type' . gettype($points));
            }
        }

        $this->points = $points;
    }

    public function toJSON()
    {
        return array('type' => 'LineString', 'coordinates' => array_map(function ($item) {
            return $item->toJSON()['coordinates'];
        }, $this->points));
    }


    public static function validate($lineString)
    {
        if ((array_key_exists('type', $lineString) && $lineString['type'] !== 'LineString') || (array_key_exists('coordinates', $lineString) && gettype($lineString['coordinates']) !== 'array')) {
            return false;
        }

        foreach ($lineString['coordinates'] as $point) {
            if (!is_numeric($point[0]) || !is_numeric($point[1])) {
                return false;
            }
        }
        return true;
    }

    public static function isClosed(LineString $lineString)
    {
        $firstPoint = $lineString->points[0];
        $lastPoint = $lineString->points[count($lineString->points) - 1];

        if ($firstPoint == $lastPoint) {
            return true;
        }
        return false;
    }
}
