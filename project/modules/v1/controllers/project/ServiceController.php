<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 14:07:41
 * @Description: 
 */

namespace project\modules\v1\controllers\project;

use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\StatusEnum;
use common\models\project\Service;
use project\controllers\OnAuthController;
use common\enums\project\ItemTypeEnum;
use common\enums\VerifyEnum;
use common\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\project\ItemHouseMap;
use common\models\project\Item;
use common\models\company\Worker;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceController extends OnAuthController
{
    public $modelClass = Service::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac', 'index'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        // 类型
        $type = $request->get('type', NULL);
        $content = $request->get('title', NULL);
        $itemWhere = [];
        if ($content) {
            $itemModel = Item::find()
                ->where(['like', 'title', $content])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            $itemIds = ArrayHelper::getColumn($itemModel, 'id', $keepKeys = true);
            $itemWhere = ['in', 'pid', $itemIds];
        }

        $order = '';

        $where = [];
        switch ($type) {
            case 'my':
                $where = ['user_id' =>  Yii::$app->user->identity->member_id];
                break;
            case 'save':
                $where = [
                    'and',
                    ['user_id' =>  Yii::$app->user->identity->member_id],
                    ['<', 'audit', VerifyEnum::WAIT]

                ];
                break;
            case 'wait':
                $where = [
                    'and',
                    ['user_id' =>  Yii::$app->user->identity->member_id],
                    ['audit' => VerifyEnum::WAIT]

                ];
                break;
            case 'pass':
                $where = [
                    'and',
                    ['user_id' =>  Yii::$app->user->identity->member_id],
                    ['audit' => VerifyEnum::PASS]
                ];
                break;
            case 'member':
                $where = [
                    'and',
                    ['manager' => Yii::$app->user->identity->member_id],
                    ['<', 'audit', VerifyEnum::WAIT]
                ];
                break;
            case 'member_wait':
                $where = [
                    'and',
                    ['manager' => Yii::$app->user->identity->member_id],
                    ['>', 'audit', VerifyEnum::SAVE]
                ];
                break;
            default:
                $where = [
                    'and',
                    ['manager' => Yii::$app->user->identity->member_id],
                    ['<', 'audit', VerifyEnum::PASS]
                ];
                break;
        }
        $model = Service::find()
            ->with(['item', 'user' => function ($queue) {
                $queue->select(['id', 'realname']);
            }, 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->where($where)
            ->andWhere($itemWhere)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['pid' => $pid])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['audit_text'] =  VerifyEnum::getValue($value['audit']);
            $value['cate_text'] = $value['cate']['title'];
        }
        unset($value);

        return $model;
    }

    /**
     * 任务列表
     * 
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;

        $content = $request->get('title', NULL);
        $itemWhere = [];
        if ($content) {
            $itemModel = Item::find()
                ->where(['like', 'title', $content])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            $itemIds = ArrayHelper::getColumn($itemModel, 'id', $keepKeys = true);
            $itemWhere = ['in', 'pid', $itemIds];
        }
        $model = Service::find()
            ->with(['item', 'user' => function ($queue) {
                $queue->select(['id', 'realname']);
            }, 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere($itemWhere)
            ->offset($start)
            ->limit($limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['audit_text'] =  VerifyEnum::getValue($value['audit']);
            $value['cate_text'] = $value['cate']['title'];
        }
        unset($value);

        return $model;
    }


    /**
     * 审核 POST
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionVerify($id)
    {

        $request = Yii::$app->request;
        $audit = $request->post('audit');       //审核状态
        $model = $this->findModel($id);
        // 项目状态为提交时才能审核
        if ($model->audit != VerifyEnum::WAIT) {
            throw new NotFoundHttpException('任务未提交!');
        }
        $model->audit = $audit ? VerifyEnum::PASS : VerifyEnum::OUT;
        // 修改成功，记录审核信息
        if ($model->save()) {
            if (!$audit)
                Yii::$app->services->projectService->getMiniNotice($id, 'news');
            return true;
        }
        throw new NotFoundHttpException('审批失败！');
    }

    /**
     * 撤回/提交 PUT
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionAudit($id)
    {
        $request = Yii::$app->request;
        $audit = $request->post('audit');
        $member_id = Yii::$app->user->identity->member_id;

        $model = $this->findModel($id);
        if ($member_id != $model['manager']) {
            throw new NotFoundHttpException('无权操作！');
            # code...
        }
        // 撤回:0，提交:1  其他错误
        switch ($audit) {
            case 1:
                if ($model->audit != VerifyEnum::OUT && $model->audit != VerifyEnum::SAVE) {
                    throw new NotFoundHttpException('当前状态不支持提交！');
                }
                break;
            case 0:
                if ($model->audit != VerifyEnum::WAIT) {
                    throw new NotFoundHttpException('当前状态不支持撤回！');
                }
                break;
            default:
                throw new NotFoundHttpException('系统繁忙！');
                break;
        }
        $model->audit = $audit ? VerifyEnum::WAIT : VerifyEnum::SAVE;
        if ($model->save()) {
            // 提交时发送订阅消息
            if ($audit)
                // 发送提交的微信消息
                Yii::$app->services->projectService->getMiniNotice($id, 'submit');

            // 添加日志
            Yii::$app->services->projectService->addVerifyLog($id, $model->audit);
            # code...
            return true;
        }
        return false;
    }

    // 订阅消息
    public function actionSignature()
    {
        $request = Yii::$app->request;
        $message = $request->post('message_data');
        $type = $request->post('type');

        switch ($type) {
            case 'submit':
                return Yii::$app->services->workerMiniMessage->createRemind($message['openid'], '', MessageActionEnum::VERIFY_WAIT, MessageReasonEnum::SERVICE_VERIFY);
                break;
            case 'new':
                return Yii::$app->services->workerMiniMessage->createRemind($message['openid'], '', MessageActionEnum::VERIFY_CREATE, MessageReasonEnum::SERVICE_VERIFY);
                break;
            case 'bell':
                return Yii::$app->services->workerMiniMessage->createRemind($message['openid'], '', MessageActionEnum::REMIND, MessageReasonEnum::SERVICE_VERIFY);
                break;
            default:
                # code...
                break;
        }
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
        $model = Service::find()
            ->with(['user', 'item', 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $model['start_time'] = date('Y-m-d', $model['start_time']);
        $model['end_time'] = date('Y-m-d', $model['end_time']);
        $model['manager_name'] = $model['user']['realname'] ?: '管理员';
        $model['members'] = json_decode($model['members'], true);

        $model['membersList'] = [];
        foreach ($model['members'] ?: [] as $key => $value) {
            array_push($model['membersList'], [
                'id' => $value,
                'name' => Worker::getRealname($value)
            ]);
        }

        if ($model['item']) {
            $model['item']['type'] = ItemTypeEnum::getValue($model['item']['type']);
            $model['item']['images'] = json_decode($model['item']['images'], true);
            $model['item']['file'] = json_decode($model['item']['file'], true);
            $model['item']['detail_address'] = Yii::$app->services->provinces->getCityListName([$model['item']['province_id'], $model['item']['city_id'], $model['item']['area_id']]) . $model['item']['address'];
        }


        return $model;
    }

    /**
     * 创建任务
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $item = $request->post('data');

        $model = $id ? $this->findModel($id) : new Service();
        $item['user_id'] = $id ?  $model->user_id : Yii::$app->user->identity->member_id;
        if ($model->load($item, '') && $model->save()) {
            Yii::$app->services->projectService->getMiniNotice($id, 'news');
            return true;
        }
        throw new NotFoundHttpException($this->getError($model));
    }

    /**
     * 任务默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $pid = $request->get('pid', NULL);

        $itemModel = new Service();
        $model = $itemModel->loadDefaultValues();
        if ($id) {
            $model = Service::find()
                ->with('item')
                ->where(['id' => $id])
                ->asArray()
                ->one();
        }
        $ItemHouseMapModel = [];
        $ItemHouseMapModel = ItemHouseMap::find()
            ->with(['house' => function ($queue) {
                $queue->select(['id', 'title']);
            }])
            ->where(['item_id' => $pid])
            ->asArray()
            ->all();

        return [
            'model' => $model,
            'items' => Item::getDropDown(),
            'ItemHouseMapModel' => $ItemHouseMapModel,
        ];
    }
}
