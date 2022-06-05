<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 16:37:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-01 16:37:44
 * @Description: 
 */

namespace backend\modules\project;

use Yii;

/**
 * member module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\project\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Yii::$app->services->merchant->addId(0);
        // custom initialization code goes here
    }
}
