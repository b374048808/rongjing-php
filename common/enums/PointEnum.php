<?php

namespace common\enums;


/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PointEnum extends BaseEnum
{
    const ANGLE = 1;
    const CRACKS = 2;
    const SINK = 3;
    const MOVE = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ANGLE => '倾斜',
            self::CRACKS => '裂缝',
            self::SINK => '沉降',
            self::MOVE => '平顶位移',
        ];
    }

    /**
     * @return array
     */
    public static function getSymbolMap(): array
    {
        return [
            self::ANGLE => 'angle',
            self::CRACKS => 'cracks',
            self::SINK => 'sink',
            self::MOVE => 'move',
        ];
    }

    /**
     * @return array
     */
    public static $Until = [
        self::ANGLE => '%',
        self::CRACKS => 'mm',
        self::SINK => 'mm',
        self::MOVE => 'mm',
    ];

    /**
     * @return array
     */
    public static function getAlert($id)
    {
        $data = [
            self::ANGLE => '需要通过连续两个月倾斜率对比来判断房屋是否倾斜严重',
        ];
        return $data[$id];
    }

    /**
     * @return array
     */
    public static function getSymbolValue($id)
    {
        return static::getSymbolMap()[$id] ?: '';
    }
}
