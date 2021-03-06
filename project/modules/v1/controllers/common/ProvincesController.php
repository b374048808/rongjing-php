<?php

namespace project\modules\v1\controllers\common;

use Yii;
use project\controllers\OnAuthController;
use common\models\common\Provinces;

/**
 * Class ProvincesController
 * @package project\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class ProvincesController extends OnAuthController
{
    /**
     * @var Provinces
     */
    public $modelClass = Provinces::class;

    /**
     * 获取省市区
     *
     * @param int $pid
     * @return array|yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $pid = Yii::$app->request->get('pid', 0);

        return Yii::$app->services->provinces->getCityByPid($pid);
    }
}
