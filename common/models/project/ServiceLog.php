<?php

namespace common\models\project;

use Yii;

/**
 * This is the model class for table "rf_rj_project_service_log".
 *
 * @property int $id
 * @property int $user_id 人员ID
 * @property int $pid 任务id
 * @property int $verify 审核
 * @property string $ip ip地址
 * @property string $remark 备注
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ServiceLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_service_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'verify', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['ip'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'pid' => 'Pid',
            'verify' => 'Verify',
            'ip' => 'Ip',
            'remark' => 'Remark',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
