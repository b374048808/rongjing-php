<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 17:49:24
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 14:41:55
 * @Description: 
 */

namespace common\models\project;

use Yii;

/**
 * This is the model class for table "rf_rj_project_item_contract".
 *
 * @property int $id
 * @property int $pid 项目
 * @property string $money 金额
 * @property int $user_id 人员
 * @property int $event_time 签约日期
 * @property array $file 合同文件
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Contract extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'user_id', 'event_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['money'], 'number'],
            [['file'], 'safe'],
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
            'money' => 'Money',
            'user_id' => 'User ID',
            'event_time' => 'Event Time',
            'file' => 'File',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
