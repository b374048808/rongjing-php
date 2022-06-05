<?php

namespace common\models\company;

use Yii;

/**
 * This is the model class for table "rf_rj_company_worker_mini_message_log".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property int $pid 消息id
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property int $use_time 使用时间
 * @property string $ip ip地址
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property array $message_data 发送内容
 */
class MiniMessageLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_company_worker_mini_message_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'pid', 'error_code', 'use_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['error_data'], 'string'],
            [['message_data'], 'safe'],
            [['error_msg'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'pid' => 'Pid',
            'error_code' => 'Error Code',
            'error_msg' => 'Error Msg',
            'error_data' => 'Error Data',
            'use_time' => 'Use Time',
            'ip' => 'Ip',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'message_data' => 'Message Data',
        ];
    }
}
