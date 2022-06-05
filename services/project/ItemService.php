<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-11 09:56:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 10:16:28
 * @Description: 
 */

namespace services\project;

use Yii;
use common\components\Service;
use common\models\project\Log;
use common\enums\VerifyEnum;
use common\models\company\MiniMessage;
use common\models\company\Worker;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\project\ItemTypeEnum;
use common\models\project\Item;
use common\models\project\Steps;
use common\models\project\StepsMember;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class ItemService extends Service
{
    public function addLog($id, $audit = true, $action = "", $desc = '')
    {
        $member_id = Yii::$app->user->identity->member_id;
        $verifyModel = new Log();
        $verifyModel->user_id = $member_id;
        $verifyModel->map_id = $id;
        $verifyModel->ip = Yii::$app->request->userIP;
        $verifyModel->verify = $audit ? 1 : 0;
        $auditText = $audit ? '通过' : '驳回';
        $verifyModel->action = $action;
        $verifyModel->remark = Worker::getRealname($member_id) . '审核' . $auditText;
        $verifyModel->description = $desc;
        $verifyModel->save();
        return $verifyModel;
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
    public function getMiniNotice($id, $step_id, $options = [])
    {
        // 步骤
        $stepsModel = Steps::findOne($step_id);
        // 遍历当前步骤下的有权限用户
        $memberIds = StepsMember::getMemberColumn($stepsModel['id']);
        // 当前项目
        $model  = Item::findOne($id);
        // 步骤任务和归档同一模版
        switch ($stepsModel['name']) {
            case 'VERIFY':
                // 订阅项目审核步骤
                $messageModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::VERIFY_WAIT,
                        'target_type' => MessageReasonEnum::ITEM_VERIFY
                    ])
                    ->andWhere(['in', 'member_id', $memberIds])
                    ->groupBy(['member_id'])
                    ->asArray()
                    ->all();
                $data = [
                    'thing1' => [
                        'value' => mb_substr($model->title, 0, 15),
                    ],
                    'thing4' => [
                        'value' => Worker::getRealname($model->user_id),
                    ],
                    'time5' => [
                        'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : '') . '~' . ($model->end_time ? date('Y-m-d', $model->end_time) : ''),
                    ],
                    'thing2' => [
                        'value' =>  ItemTypeEnum::getValue($model->type)
                    ],
                ];
                foreach ($messageModel ?: [] as $value) {
                    return Yii::$app->services->workerMiniMessage->send($value['id'], $data);
                }
                break;
            case 'NUMBER':
                // 查询订阅了编号步骤的用户
                $numberModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::NUMBER_REMIND,
                        'target_type' => MessageReasonEnum::ITEM_VERIFY
                    ])
                    ->groupBy('member_id')
                    ->asArray()
                    ->all();
                // 项目人
                $realname = Worker::getRealname($model['user_id']);
                $data = [
                    'thing1' => [
                        'value' =>  $realname
                    ],
                    'thing3' => [
                        'value' =>  $model['title']
                    ],
                ];

                foreach ($numberModel as $key => $value) {
                    Yii::$app->services->workerMiniMessage->send($value['id'], $data);
                }
                break;
                // 作业阶段
            case 'ONWORK':
                // 订阅人员为项目发起者
                $numberModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::STEPS_END,
                        'target_type' => MessageReasonEnum::ITEM_STEPS,
                        'member_id' => $model['user_id']
                    ])
                    ->groupBy('member_id')
                    ->asArray()
                    ->one();
                $data = [
                    'character_string1' => [
                        'value' => $model->number ?: date('Ymd'),
                    ],
                    'thing2' => [
                        'value' => $model->title ?: '空',
                    ],
                    'thing3' => [
                        'value' => $stepsModel['title'] ?: '空',
                    ],
                    'time4' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                ];
                if ($numberModel)
                    Yii::$app->services->workerMiniMessage->send($numberModel['id'], $data);
                break;
            case 'MONEY':
                // 订阅人员为项目发起者
                $messageModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::STEPS_MONEY,
                        'target_type' => MessageReasonEnum::ITEM_STEPS
                    ])
                    ->groupBy('member_id')
                    ->asArray()
                    ->all();
                // 有订阅
                $data = [
                    'thing1' => [
                        'value' => mb_substr($model->title, 0, 15),
                    ],
                    'time2' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                    'amount3' => [
                        'value' => $model->money ?: '空',
                    ],
                ];
                foreach ($messageModel as $key => $value) {
                    # code...
                    Yii::$app->services->workerMiniMessage->send($value['id'], $data);
                }
                break;
            case 'ARCHIVE':
                // 订阅人员为项目发起者
                $messageModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::STEPS_END,
                        'target_type' => MessageReasonEnum::ITEM_STEPS,
                    ])
                    ->andWhere(['in', 'member_id', $memberIds])
                    ->asArray()
                    ->all();
                // 有订阅
                $data = [
                    'character_string1' => [
                        'value' => $model->number ?: '空',
                    ],
                    'thing2' => [
                        'value' => $model->title ?: '空',
                    ],
                    'thing3' => [
                        'value' => $stepsModel['title'],
                    ],
                    'time4' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                ];
                foreach ($messageModel as $key => $value) {
                    # code...
                    Yii::$app->services->workerMiniMessage->send($value['id'], $data);
                }
                break;
            default:
                # code...
                break;
        }
        return true;
    }

    public function MiniVerifyNotice($id, $audit = true, $desc = '')
    {

        $model  = Item::findOne($id);
        // 微信通知
        $messageModel = MiniMessage::findOne([
            'is_read' => 0,
            'action' => MessageActionEnum::VERIFY_SUCCESS,
            'target_type' => MessageReasonEnum::ITEM_VERIFY,
            'target_id' => $model->id
        ]);

        $realname = Worker::getRealname(Yii::$app->user->identity->member_id);

        $data = [
            'thing6' => [
                'value' => mb_substr($model->title, 0, 15),
            ],
            'name3' => [
                'value' => $realname,
            ],
            'thing5' => [
                'value' => $desc ?: '空',
            ],
            'phrase7' => [
                'value' =>  $audit ? '通过' : '驳回'
            ],
        ];


        // 有订阅
        if ($messageModel)
            Yii::$app->services->workerMiniMessage->send($messageModel->id, $data);
    }

    public function MiniAuditNotice($id)
    {
        $model  = Item::findOne($id);
        $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
        // 提交给订阅发送消息
        $messageModel = MiniMessage::find()
            ->where([
                'is_read' => 0,
                'action' => MessageActionEnum::VERIFY_WAIT,
                'target_type' => MessageReasonEnum::ITEM_VERIFY,
            ])
            ->asArray()
            ->all();
        $data = [
            'thing1' => [
                'value' =>  $model->title,
            ],
            'thing4' => [
                'value' => $realname,
            ],
            'time5' => [
                'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : '') . '~' . ($model->end_time ? date('Y-m-d', $model->end_time) : ''),
            ],
            'thing2' => [
                'value' => ItemTypeEnum::getValue($model->type)
            ],
        ];
        // 发送订阅消息
        if ($messageModel) {
            foreach ($messageModel as $key => $value) {
                Yii::$app->services->workerMiniMessage->send($value['id'], $data);
            }
        }
    }

    public function MiniSubmitMessage($id)
    {
        $model  = Item::findOne($id);
        // 微信通知
        if ($model->audit == VerifyEnum::WAIT) {
            $messageModel = MiniMessage::find()
                ->where([
                    'is_read' => 0,
                    'action' => MessageActionEnum::VERIFY_WAIT,
                    'target_type' => MessageReasonEnum::ITEM_VERIFY
                ])
                ->asArray()
                ->all();
            $data = [
                'thing1' => [
                    'value' => mb_substr($model->title, 0, 15),
                ],
                'thing4' => [
                    'value' => Worker::getRealname($model->user_id),
                ],
                'time5' => [
                    'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : '') . '~' . ($model->end_time ? date('Y-m-d', $model->end_time) : ''),
                ],
                'thing2' => [
                    'value' =>  ItemTypeEnum::getValue($model->type)
                ],
            ];
            foreach ($messageModel ?: [] as $value) {

                return Yii::$app->services->workerMiniMessage->send($value['id'], $data, 'pages/monitor-item-view/monitor-item-view?itemId=' . $model->attributes['id']);
            }
        }
    }
}
