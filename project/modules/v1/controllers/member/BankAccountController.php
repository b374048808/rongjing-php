<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 18:02:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-01 18:11:28
 * @Description: 
 */

namespace project\modules\v1\controllers\member;

use project\controllers\UserAuthController;
use common\models\forms\BankAccountForm;

/**
 * 提现账号
 *
 * Class BankAccountController
 * @package project\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class BankAccountController extends UserAuthController
{
    /**
     * @var BankAccountForm
     */
    public $modelClass = BankAccountForm::class;
}
