<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 18:02:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-01 18:10:27
 * @Description: 
 */

namespace project\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * 报错消息处理
 *
 * Class MessageController
 * @package project\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MessageController extends Controller
{
    /**
     * @return string
     */
    public function actionError()
    {
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        return $exception->getMessage();
    }
}
