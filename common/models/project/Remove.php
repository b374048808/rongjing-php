<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-04 15:09:18
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 16:41:23
 * @Description: 
 */

namespace common\models\project;

use common\models\company\Worker;
use Yii;

/**
 * This is the model class for table "rf_rj_project_item_remove".
 *
 * @property int $id
 * @property int $pid 项目ID
 * @property int $result 结果
 * @property int $is_read 已处理
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Remove extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_remove';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'result', 'is_read', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'pid' => 'Pid',
            'result' => 'Result',
            'is_read' => 'Is Read',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'pid']);
    }

    public function getUser()
    {
        return $this->hasOne(Worker::class, ['id' => 'user_id'])
            ->viaTable(Item::tableName(), ['id' => 'pid']);
    }
}
