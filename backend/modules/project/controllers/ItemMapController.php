<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-16 16:19:51
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-02 21:19:37
 * @Description: 
 */

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\house\House;
use common\models\project\ItemHouseMap;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ItemMapController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Item::class;


    public function actionAjaxHouse($id)
    {
        // 已选择房屋列表
        $houseIds = ItemHouseMap::getHouseIds($id);


        $query = House::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['not in', 'id', $houseIds]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->renderAjax($this->action->id, [
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);
    }

    /**
     * 批量添加房屋
     * 
     * @param number id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionAddHouse()
    {

        $request = Yii::$app->request;
        $item_id = $request->get('id', NULL);
        $data = $request->post('data', []);

        return ItemHouseMap::addHouses($item_id, $data);
    }


    public function actionDelete($item_id, $house_id)
    {
        return ItemHouseMap::deleteAll([
            'and',
            ['item_id' => $item_id],
            ['house_id' => $house_id]
        ])
            ? $this->message('删除成功！', $this->redirect(Yii::$app->request->referrer), 'success')
            : $this->message('删除失败！', $this->redirect(Yii::$app->request->referrer), 'error');
    }



    /**
     * 批量删除房屋
     * 
     * @param number id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionDelHouse()
    {

        $request = Yii::$app->request;
        $item_id = $request->get('id', NULL);
        $data = $request->post('data', []);

        return ItemHouseMap::deleteAll(['and', ['item_id' => $item_id], ['in', 'house_id', $data]]);
    }
}
