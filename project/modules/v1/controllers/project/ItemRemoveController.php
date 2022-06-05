<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 16:40:07
 * @Description: 
 */

namespace project\modules\v1\controllers\project;

use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\project\Item;
use project\controllers\OnAuthController;
use common\models\project\Remove;
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
class ItemRemoveController extends OnAuthController
{
    public $modelClass = Remove::class;

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

        $model = Remove::find()
            ->with(['item', 'user'])
            ->andWhere(['status' => StatusEnum::ENABLED, 'is_read' => 0])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        return $model;
    }

    public function actionView($id)
    {

        $model = $this->findModel($id);

        return  $model;
    }


    /**
     * 项目撤销申请
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $model = $this->findModel($id);
        if ($id) {
            $audit = $request->post('audit', NULL);
            $desc = $request->post('description', NULL);
            if ($audit) {
                Item::updateAll(['verify' => VerifyEnum::SAVE, 'steps_name' => 'VERIFY'], ['id' => $model->pid]);
            }
            $model->description = $desc;
            $model->is_read = 1;
            $model->result = $audit ? 1 : 0;
            return $model->save() ?: $this->getError($model);
        }
        return $model;
    }
}
