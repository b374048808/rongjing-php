<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 16:38:05
 * @Description: 
 */

namespace project\modules\v1\controllers\house;

use common\enums\StatusEnum;
use common\models\house\House;
use common\models\project\ItemHouseMap;
use project\controllers\OnAuthController;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends OnAuthController
{
    public $modelClass = House::class;

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

        // 根据项目搜索关联房屋
        $where = [];

        $model = House::find()
            ->select(['title', 'id', 'status'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $title])
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
        $title = $request->get('title', NULL);
        $item_id = $request->get('item_id', NULL);
        $service_id = $request->get('service_id', NULL);
        $not_in = $request->get('not_in', NULL);
        $ids =  $request->get('ids', NULL);

        // 根据项目搜索关联房屋
        $where = [];
        if ($item_id) {
            $isIn = $not_in ? 'not in' : 'in';
            $where = [$isIn, 'id', ItemHouseMap::getHouseIds($item_id)];
        } else if ($ids) {
            $houseId = json_decode($ids, true);
            $isIn = $not_in ? 'not in' : 'in';
            $where = [$isIn, 'id', $houseId];
        }


        $model = House::find()
            ->select(['title', 'id', 'status'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $title])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        return $model;
    }

    /**
     * 详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);


        return $model;
    }

    /**
     * 提交编辑
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->post('id', '');

        $model = $id ? House::findOne($id) : new House();
        if ($model->load($request->post(), '') && $model->save()) {
            return true;
        }

        throw new NotFoundHttpException($this->getError($model));
    }


    /**
     * 编辑界面/默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $itemModel = new House();
        if ($id) {
            $model = House::find()
                ->where(['id' => $id])
                ->asArray()
                ->one();
        } else {
            $model = $itemModel->loadDefaultValues();
        }
        $model['layout_cover'] = json_decode($model['layout_cover']);
        $model['plan_cover'] = json_decode($model['plan_cover']);

        return [
            'model' => $model,
        ];
    }
}
