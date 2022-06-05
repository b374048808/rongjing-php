<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 17:49:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 10:31:32
 * @Description: 
 */

namespace common\models\project;

use common\models\company\Worker;
use Yii;

/**
 * This is the model class for table "rf_rj_project_item_log".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $map_id 关联id
 * @property int $verify 审核状态
 * @property int $steps_name 步骤状态
 * @property string $ip ip地址
 * @property string $description 描述
 * @property string $remark 备注
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Log extends \common\models\base\BaseModel
{

    const ACTION_VERIFY = 'verify';
    const ACTION_STEPS = 'steps';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'map_id', 'verify', 'steps_name', 'status', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 30],
            [['action'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 140],
            [['remark'], 'string', 'max' => 200],
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
            'map_id' => 'Map ID',
            'verify' => 'Verify',
            'steps_name' => 'Steps Name',
            'ip' => 'Ip',
            'description' => 'Description',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Worker::class, ['id' => 'user_id']);
    }
}
