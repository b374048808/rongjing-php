<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 15:01:16
 * @Description: 
 */

namespace project\modules\v1\controllers\project;

use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\project\ItemTypeEnum;
use common\enums\VerifyEnum;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use project\controllers\OnAuthController;
use yii\web\NotFoundHttpException;
use common\models\company\Worker;
use common\models\project\Config;
use common\models\project\Item;
use common\models\project\ItemHouseMap;
use common\models\project\Log;
use common\models\project\Steps;
use common\models\project\StepsMember;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemController extends OnAuthController
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


    /**
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 用户可以查询的列表
     */
    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称
        $steps_name = $request->get('steps_name', NULL);    //步骤
        $user_id = $request->get('user', NULL) ? Yii::$app->user->identity->member_id : '';
        $type = $request->get('type', NULL);      //名称

        $where = [];
        $stepsModel = Steps::findOne(['name' => $steps_name]);
        if ($stepsModel) {
            $roles = StepsMember::getMemberColumn($stepsModel['id']);
            if (!in_array(Yii::$app->user->identity->member_id, Yii::$app->params['adminAccount']) && !in_array(Yii::$app->user->identity->member_id, $roles)) {
                throw new \yii\web\BadRequestHttpException('对不起，您现在还没获此操作的权限');
            }
            $where = [
                'and',
                ['steps_name' =>  $steps_name],
                ['verify' => VerifyEnum::PASS]
            ];
            if ($stepsModel['name'] == 'VERIFY') {
                $where = [
                    'and',
                    ['steps_name' =>  $steps_name],
                    ['verify' => VerifyEnum::WAIT]
                ];
            }
        }


        switch ($type) {
            case 'wait':
                $where = ['=', 'verify', VerifyEnum::WAIT];
                break;
            case 'save':
                $where = ['<', 'verify', VerifyEnum::WAIT];
                break;
            case 'pass':
                $stepsModel = Steps::findOne(['name' => 'SAVE']);
                $where = ['=', 'steps_name', $stepsModel['name']];
                break;
            default:
                # code...
                break;
        }

        // 判断是否自己提交，不然只显示能操作的项目
        $model = Item::find()
            ->with(['user'])
            ->select(['id', 'number', 'user_id', 'title', 'status', 'verify', 'start_time', 'end_time', 'steps_name'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['user_id' => $user_id])
            ->andFilterWhere([
                'or',
                ['like', 'title', $title],
                ['like', 'number', $title]
            ])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            # code...   
            $value['steps_text'] = Steps::getNameTitle($value['steps_name']);
            $value['audit_text'] = VerifyEnum::getValue($value['verify']);
            $value['tag_type'] = ($value['verify'] > VerifyEnum::SAVE);
        }
        unset($value);

        return $model;
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 总列表，需要权限查看
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称

        $model = Item::find()
            ->with(['user'])
            ->select(['id', 'number', 'user_id', 'title', 'steps', 'status', 'audit', 'start_time', 'end_time', 'steps_name'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere([
                'or',
                ['like', 'title', $title],
                ['like', 'number', $title]
            ])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            # code...   
            $value['steps_text'] = Steps::getNameTitle($value['steps_name']);
            $value['audit_text'] = VerifyEnum::getValue($value['audit']);
            $value['tag_type'] = ($value['audit'] > VerifyEnum::SAVE);
        }
        unset($value);

        return $model;
    }

    /**
     * 项目详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionView($id)
    {
        // 关联审核记录，合同，最新审核记录，其他配置
        $model = Item::find()
            ->with(['verifyLog', 'contracts', 'newVerifyLog', 'config', 'services', 'stepsLog' => function ($queue) {
                $queue->orderBy('id desc');
            }, 'newStepsLog'])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        // 转换其他配置信息为数组
        $list = [];
        // 类型数据转换,判断数据类型是否为数组
        if ($model['config']) {
            $type = json_decode($model['config']['type'], true);
            if (is_array($type)) {
                foreach ($type as $key => $value) {
                    array_push($list, PointEnum::getValue($value));
                    # code...
                }
            }
            $model['config']['type'] = $list;
        }
        // 遍历记录中的时间
        foreach ($model['verifyLog'] as $key => &$value) {
            $value['time'] = date('Y-m-d H:i:s', $value['created_at']);
            # code...
        }
        unset($value);
        // 转化关联合同的格式
        foreach ($model['contracts'] ?: [] as $key => $value) {
            $model['contracts'][$key]['file'] = $value['file'] ? json_decode($value['file'], true) : [];
            $model['contracts'][$key]['event_time']  = date('Y-m-d', $value['event_time']);
            $model['contracts'][$key]['manager'] = $value['manager'] > 0 ? Worker::getRealname($value['manager']) : '';
        }
        foreach ($model['services'] as $key => &$value) {
            $value['status'] = VerifyEnum::getValue($value['audit']);
        }
        unset($value);
        // 转化格式
        // StringHelper::getThumbUrl($url, $width, $height)缩略图
        $model['steps_text'] = Steps::getNameTitle($model['steps_name']);   //步骤编号
        $model['detail_address'] = Yii::$app->services->provinces->getCityListName([$model['province_id'], $model['city_id'], $model['area_id']]) . $model['address'];
        $model['type'] = ItemTypeEnum::getValue($model['type']);
        $model['images'] = $model['images'] ? json_decode($model['images'], true) : [];
        $model['collection'] = json_decode($model['collection'], true);
        $model['type_text'] = ItemTypeEnum::getValue($model['type']);
        $model['audit_text'] = VerifyEnum::getValue($model['verify']);
        $model['start_time'] = $model['start_time'] ? date('Y-m-d', $model['start_time']) : '';
        $model['end_time'] = $model['end_time'] ? date('Y-m-d', $model['end_time']) : '';
        $model['file'] = $model['file'] ? json_decode($model['file'], true) : '';
        $model['role'] = StepsMember::getRole(Yii::$app->user->identity->member_id, $model['steps']);
        $model['map_list'] = ItemHouseMap::getHouseIds($id);


        return $model;
    }

    /**
     * 编辑
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $config_item = $request->post('config_item', []);
        $message_data = $request->post('message_data', '');

        $model = $id ? $this->findModel($id) : new Item();
        $item = $request->post('data');
        $item['user_id'] = Yii::$app->user->identity->member_id;
        $item['verify'] = $id ? $model->verify : VerifyEnum::WAIT;
        $item['steps_name'] = $id ? $model->steps_name : 'VERIFY';
        $db = Yii::$app->db;
        // 在主库上启动事务
        $transaction = $db->beginTransaction();
        try {
            // 项目创建
            if ($model->load($item, '') && $model->save()) {
                // 记录用户点阅了微信消息项目审批结果
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::ITEM_VERIFY);
                // 如果是监测项目,创建对应的监测信息
                if ($model['type'] == ItemTypeEnum::MONITOR) {
                    $configModel = $config_item['id'] ? Config::findOne($config_item['id']) : new Config();
                    $config_item['pid'] = $model->attributes['id'];
                    if ($configModel->load($config_item, '') && $configModel->save()) {
                    } else {
                        throw new NotFoundHttpException($this->getError($configModel));
                    }
                }
                $transaction->commit();
                // 创建直接提交，项目状态为提交，给审批人发送订阅消息
                if ($model->verify == VerifyEnum::WAIT) {
                    $stepsModel = Steps::findOne(['name' => 'VERIFY']);
                    Yii::$app->services->projectItem->getMiniNotice($id, $stepsModel['id']);
                }
                return true;
            }
            return $this->getError($model);
            throw new NotFoundHttpException($this->getError($model));
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        throw new NotFoundHttpException($this->getError($model));
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
        $desc = $request->post('description', NULL);   //审核备注

        $model = $this->findModel($id);
        // 项目状态为提交时才能审核
        if ($model->verify != VerifyEnum::WAIT) {
            throw new NotFoundHttpException('项目未提交!');
        }

        $model->verify = $audit ? VerifyEnum::PASS : VerifyEnum::OUT;
        // 审批步骤
        if ($model->save()) {
            // 添加日志
            Yii::$app->services->projectItem->addLog($id, $audit, Log::ACTION_VERIFY, $desc);
            // 如果通过,变更项目步骤
            if ($audit)
                return $this->getSteps($id, $audit);
            // 是否通过，发送项目提交的人审核结果消息
            Yii::$app->services->projectItem->MiniVerifyNotice($id, $audit, $desc);
            return true;
        }
        throw new NotFoundHttpException('审批失败！');
    }


    /**
     * 步骤确认
     * @param {*} $id
     * @return {*}
     * @throws: 判断下一步步骤，发送下个步骤的订阅消息
     */
    public function actionSteps($id)
    {
        $request = Yii::$app->request;

        $audit = $request->post('audit', true);
        $desc = $request->post('description', '');
        Yii::$app->services->projectItem->addLog($id, $audit, Log::ACTION_STEPS, $desc);
        // 判断步骤
        return $this->getSteps($id, $audit);
    }

    /**
     * 撤回/提交
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
        if ($member_id != $model['user_id']) {
            throw new NotFoundHttpException('无权操作！');
            # code...
        }
        // 撤回:0，提交:1  其他错误
        switch ($audit) {
            case 1:
                if ($model->verify != VerifyEnum::OUT && $model->verify != VerifyEnum::SAVE) {
                    throw new NotFoundHttpException('当前状态不支持提交！');
                }
                break;
            case 0:
                if ($model->verify < VerifyEnum::WAIT) {
                    throw new NotFoundHttpException('当前状态不支持撤回！');
                }
                break;
            default:
                throw new NotFoundHttpException('系统繁忙！');
                break;
        }
        // 如果是提交,变更项目步骤为审核,
        $model->verify = $audit ? VerifyEnum::WAIT : VerifyEnum::SAVE;
        $stepsModel = Steps::findOne(['name' => 'VERIFY']);
        $model->steps_name = 'VERIFY';
        if ($model->save()) {
            if ($audit) {
                // 添加日志 未实现
                // Yii::$app->services->projectItem->addLog($id, $model->verify);
                // 小程序审核人提醒
                Yii::$app->services->projectItem->getMiniNotice($id, $stepsModel['id']);
            }
        }
        return $model;
    }


    /**
     * 编辑默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $itemModel = new Item();
        $model = $id  ? Item::findOne($id) : $itemModel->loadDefaultValues();
        return [
            'model' => $model,
            'typeEnum'  => PointEnum::getMap(),
            'config_model' => Config::findOne(['pid' => $model['id']]),
            'cate_enum'  => ItemTypeEnum::getMap(),
            'mapList' => $id ? ItemHouseMap::getHouseIds($id) : [],
        ];
    }

    //发送摇号订阅消息
    public function actionSignature()
    {
        $request = Yii::$app->request;
        $message_data =  $request->post('message_data');
        $type = $request->post('type', '');
        switch ($type) {
            case 'verify':
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::VERIFY_WAIT, MessageReasonEnum::ITEM_VERIFY);
                break;
            case 'number':
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::NUMBER_REMIND, MessageReasonEnum::ITEM_VERIFY);
                break;
            case 'onwork':
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::STEPS_END, MessageReasonEnum::ITEM_STEPS);
                break;
            case 'money':
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::STEPS_MONEY, MessageReasonEnum::ITEM_STEPS);
                break;
            case 'archive':
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::STEPS_END, MessageReasonEnum::ITEM_STEPS);
                break;
            default:
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::NUMBER_REMIND, MessageReasonEnum::ITEM_VERIFY);
                break;
        }
        return true;
    }

    public function getSteps($id, $audit = true)
    {
        $model = Item::findOne($id);
        // 判断步骤
        $stepsModel  = $audit ? Steps::getNext($model['steps_name']) : Steps::getPrev($model['steps_name']);

        // 更新，根据下一步的目标，发送订阅消息
        if ($stepsModel && Item::updateAll(['steps_name' => $stepsModel['name']], ['id' => $id])) {
            if ($stepsModel['name'] == 'VERIFY')
                Item::updateAll(['verify' => VerifyEnum::SAVE], ['id' => $id]);
            // 根据下一步骤的ID发送对应订阅的消息
            Yii::$app->services->projectItem->getMiniNotice($id, $stepsModel['id']);
            return true;
        }
        return false;
    }
}
