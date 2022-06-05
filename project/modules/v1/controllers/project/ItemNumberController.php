<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 11:14:53
 * @Description: 
 */

namespace project\modules\v1\controllers\project;

use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\project\Item;
use common\models\project\Number;
use project\controllers\OnAuthController;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemNumberController extends OnAuthController
{
    public $modelClass = Number::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        $empty = $request->get('empty', NULL);

        $where = [];
        if ($empty) {
            $where = ['number' => NULL];
        }

        $model = Item::find()
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED, 'verify' => VerifyEnum::PASS])
            ->andFilterWhere([
                'or',
                ['like', 'title', $title],
                ['like', 'number', $title]
            ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        return $model;
    }

    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        $title = $request->get('title', NULL);

        $model = Number::find()
            ->with('item')
            ->andFilterWhere(['pid' => $pid])
            ->andFilterWhere(['like', 'title', $title])
            ->offset($start)
            ->limit($limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return $model;
    }

    public function actionEdit()
    {
        $request = Yii::$app->request;

        $model = new Number();

        return ($model->load($request->post(), '') && $model->save())
            ? true
            : $this->getError($model);
    }


    public function actionDelete($id)
    {
        return Number::deleteAll(['id' => $id]);
    }
}
