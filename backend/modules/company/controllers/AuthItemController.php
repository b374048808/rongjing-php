<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-02 21:01:29
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-02 21:02:38
 * @Description: 
 */

namespace backend\modules\company\controllers;

use common\enums\AppEnum;
use common\models\rbac\AuthItem;
use common\traits\AuthItemTrait;
use backend\controllers\BaseController;

/**
 * Class AuthItemController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class AuthItemController extends BaseController
{
    use AuthItemTrait;

    /**
     * @var AuthItem
     */
    public $modelClass = AuthItem::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::PROJECT;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/company/views/auth-item/';
}
