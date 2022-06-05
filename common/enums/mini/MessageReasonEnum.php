<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 14:46:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-03 12:00:57
 * @Description: 
 */

namespace common\enums\mini;

/**
 * SubscriptionReasonEnum
 *
 * Class SubscriptionReasonEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MessageReasonEnum
{
    // 提醒关联的目标类型组别

    /**
     * 行为创建
     */
    const SERVICE_VERIFY = 'service_verify';
    const ITEM_VERIFY  = 'item_verify';
    const ITEM_STEPS = 'item_steps';
    const REPORT_VERIFY = 'report_verify';

    // 订阅原因对应订阅事件
    public static $actionTemplate = [
        self::SERVICE_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => '_frVxCwwm6GOdOcqf8lwbI82H2VVHwk3Sid1GXtgfJk',     //审核结果
            MessageActionEnum::VERIFY_WAIT => 'WhyHzaAfOh_isF4tKAzO9XsbJccyhKXal76FKWdrQI8',        //有待审核
            MessageActionEnum::VERIFY_CREATE => 'FgCGgYJtQvqgRQ0GlrQauHrtt4F7wDozr1PdKQuZmfY',      //新任务
            MessageActionEnum::REMIND => 'Nl7QMiULsnCoDBK_6HhHWaiyKaVcDYfPoEdMNqPqBVY',             //任务开始通知
        ],
        self::ITEM_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => '_frVxCwwm6GOdOcqf8lwbI82H2VVHwk3Sid1GXtgfJk',     //审核结果
            MessageActionEnum::VERIFY_WAIT => 'tqbc4WnlhD5Eci382vRz1mPowQce4srQoF9jhgKG34k',        //有项目提交
            MessageActionEnum::NUMBER_REMIND => 'lptaUnuAuTWsN47UADcRK-0xmaIj4AZwkPrlUaUuQ_0',        //有项目编号待审批
        ],
        self::ITEM_STEPS => [
            MessageActionEnum::STEPS_CONTART => 'zEh4DuhmKfxKRgl_8BEzICxkcE0yQniDQkFMURSeW_Q',      //合同
            MessageActionEnum::STEPS_SERVICE => 'bZo03Fka9__ydtBitd63QvY4L93I5TM5Rc-A1xBVrlQ',      //任务
            MessageActionEnum::STEPS_MONEY => 'kc-tS2rBvPgl07AuR1YKQdm1EivZ1pkiqG4OQYcXtic',         //项目进入收款阶段
            MessageActionEnum::STEPS_END => '3sfv-W670REpObjT0XF7hnrHVrh3rkzzm4s67nfbxR4',           //项目完成
            MessageActionEnum::STEPS_OUT => 'GxRGwkSuyb2fjuzY6vUH0CBzDz0IuuFmTpCYk5lpzgo',           //进度驳回
        ],
        self::REPORT_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => 'SAzV0m5ZvgPqqHTeo0j93l1P9_e4L0pNVaSPyt2hckg',      //审核
            MessageActionEnum::VERIFY_CREATE => 'P9DXO8EX0Wc2reO1Mlj4GcdrFmwBDsKHEWE1qvpko3A',      //提交
        ],
    ];
}
