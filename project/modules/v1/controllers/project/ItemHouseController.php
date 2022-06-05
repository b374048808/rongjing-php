<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-23 14:39:54
 * @Description: 
 */

namespace project\modules\v1\controllers\project;

use project\controllers\OnAuthController;
use common\models\project\Item;
use common\helpers\ArrayHelper;
use common\models\house\House;
use common\models\project\ItemHouseMap;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemHouseController extends OnAuthController
{
    public $modelClass = Item::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac'];


    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);      //名称
        $title = $request->get('title', NULL);

        $where = [];

        if ($title) {
            $houseModel = House::find()
                ->where(['like', 'title', $title])
                ->asArray()
                ->all();
            $ids = ArrayHelper::getColumn($houseModel, 'id', $keepKeys = true);
            $where = ['in', 'house_id', $ids];
            # code...
        }

        $model = ItemHouseMap::find()
            ->with(['house'])
            ->where($where)
            ->andWhere(['item_id' => $pid])
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        return $model;
    }

    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);      //名称
        $ids = ItemHouseMap::getHouseIds($id);
        $title = $request->get('title', NULL);

        $where = [];



        $model = House::find()
            ->where($where)
            ->andWhere(['not in', 'id', $ids])
            ->andFilterWhere(['like', 'title', $title])
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        return $model;
    }

    /**
     * 创建关联子任务
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $result = $request->post('result', []);

        return ItemHouseMap::addHouses($id, $result);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $ids = $request->post('ids', []);

        return ItemHouseMap::deleteAll([
            'and',
            ['item_id' => $id],
            ['in', 'house_id', $ids]
        ]);
    }
}
