<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 16:37:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-01 16:37:24
 * @Description: 
 */

namespace backend\modules\house;

use Yii;

/**
 * member module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\house\controllers';

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
