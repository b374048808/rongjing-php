<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 18:02:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-01 18:11:46
 * @Description: 
 */

namespace project\modules\v1\controllers;

use common\traits\FileActions;
use project\controllers\OnAuthController;

/**
 * 资源上传控制器
 *
 * Class FileController
 * @package project\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class FileController extends OnAuthController
{
    use FileActions;

    /**
     * @var string
     */
    public $modelClass = '';
}
