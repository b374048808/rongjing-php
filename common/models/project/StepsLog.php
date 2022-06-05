<?php

namespace common\models\project;

use Yii;

/**
 * This is the model class for table "rf_rj_project_item_steps_log".
 *
 * @property int $id
 * @property int $pid 关联id
 * @property int $member_id 用户id
 * @property string $description 描述
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property int $verify 审核
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property string $steps_name 原先步骤
 */
class StepsLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_steps_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'member_id', 'verify', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 140],
            [['remark'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 30],
            [['steps_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'member_id' => 'Member ID',
            'description' => 'Description',
            'remark' => 'Remark',
            'ip' => 'Ip',
            'verify' => 'Verify',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'steps_name' => 'Steps Name',
        ];
    }
}
