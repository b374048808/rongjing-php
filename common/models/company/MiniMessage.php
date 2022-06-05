<?php

namespace common\models\company;

use Yii;

/**
 * This is the model class for table "rf_rj_company_worker_mini_message".
 *
 * @property int $id 主键
 * @property string $open_id 用户
 * @property int $member_id 用户
 * @property int $target_id 目标id
 * @property string $target_type 目标类型
 * @property string $action 动作
 * @property int $is_read 是否已读 1已读
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MiniMessage extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_company_worker_mini_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['open_id'], 'required'],
            [['member_id', 'target_id', 'is_read', 'status', 'created_at', 'updated_at'], 'integer'],
            [['open_id'], 'string', 'max' => 140],
            [['target_type', 'action'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'open_id' => 'Open ID',
            'member_id' => 'Member ID',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'action' => 'Action',
            'is_read' => 'Is Read',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
