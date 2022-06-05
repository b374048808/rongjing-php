<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-18 10:04:37
 * @Description: 
 */

namespace common\enums\project;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ItemTypeEnum extends BaseEnum
{

    const DETECT = 1;
    const MONITOR = 2;
    const CHECK = 3;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DETECT => '鉴定',
            self::MONITOR => '监测',
            self::CHECK => '排查',
        ];
    }
}
