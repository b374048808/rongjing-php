<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-22 11:02:52
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 14:11:56
 * @Description: 
 */

namespace services\project;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\project\ItemTypeEnum;
use common\models\project\ServiceLog;
use common\enums\VerifyEnum;
use common\models\project\Service as ServiceModel;
use common\models\company\MiniMessage;
use common\models\company\Worker;

/**
 * Class NotifyService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceService extends Service
{

    public function addVerifyLog($id, $audit, $desc = '')
    {
        $member_id = Yii::$app->user->identity->member_id;
        $verifyModel = new ServiceLog();
        $verifyModel->user_id = $member_id;
        $verifyModel->pid = $id;
        $verifyModel->ip = Yii::$app->request->userIP;
        $verifyModel->verify = $audit;
        $verifyModel->remark = Worker::getRealname($member_id) . VerifyEnum::getAudit($audit);
        $verifyModel->description = $desc;
        return $verifyModel->save();
    }


    /**
     * 
     * 
     * @param {*} $id 项目
     * @param {*} $type 当前类型
     * @param {*} $options  额外参数
     * @return {*}
     * @throws: 
     */
    public function getMiniNotice($id, $type, $options = [])
    {
        // 当前项目
        $model  = ServiceModel::findOne($id);
        // 步骤任务和归档同一模版
        switch ($type) {
                // 创建，发送消息给订阅的人
            case 'news':
                // 负责人，订阅任务创建
                $messageModel = MiniMessage::find()
                    ->where(['member_id' => $model->manager, 'is_read' => 0])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['target_type' => MessageReasonEnum::SERVICE_VERIFY, 'action' => MessageActionEnum::VERIFY_CREATE])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                    ],
                    'thing2' => [
                        'value' => $model->description ? mb_substr($model->description, 0, 20) : '空'
                    ],
                    'time3' => [
                        'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : date('Y-m-d')),
                    ],
                    'name4' => [
                        'value' => $model->user_id ? Worker::getRealname($model->user_id) : '管理员'
                    ],
                    'thing14' => [
                        'value' => ItemTypeEnum::getValue($model->item->type),
                    ],
                ];
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel->id, $data);
                break;
                // 有任务提交
            case 'submit':
                // 发布者是否订阅了任务提交提醒
                $messageModel = MiniMessage::find()
                    ->where([
                        'member_id' => $model->user_id,
                        'is_read' => 0,
                        'action' => MessageActionEnum::VERIFY_WAIT,
                        'target_type' => MessageReasonEnum::SERVICE_VERIFY,
                    ])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => '[任务]' . mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                    ],
                    'thing2' => [
                        'value' => $model->description ? mb_substr($model->description, 0, 20) : '空',
                    ],
                    'name3' => [
                        'value' => Worker::getRealname($model->manager),
                    ],
                    'thing6' => [
                        'value' => '任务已完成，请及时审核！'
                    ],
                ];
                // 发送消息
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel->id, $data);
            case 'bell':
                // 发布者是否订阅了任务提交提醒
                $messageModel = MiniMessage::find()
                    ->where([
                        'member_id' => $model->user_id,
                        'is_read' => 0,
                        'action' => MessageActionEnum::VERIFY_WAIT,
                        'target_type' => MessageReasonEnum::SERVICE_VERIFY,
                    ])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => '[任务]' . mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                    ],
                    'thing2' => [
                        'value' => $model->description ? mb_substr($model->description, 0, 20) : '空',
                    ],
                    'name3' => [
                        'value' => Worker::getRealname($model->manager),
                    ],
                    'thing6' => [
                        'value' => '任务已完成，请及时审核！'
                    ],
                ];
                // 发送消息
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel->id, $data);
                break;
            default:
                # code...
                break;
        }
        return true;
    }
}
