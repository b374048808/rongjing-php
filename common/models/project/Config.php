<?php

namespace common\models\project;

use Yii;

/**
 * This is the model class for table "rf_rj_project_item_config".
 *
 * @property int $id 主键
 * @property int $pid 绑定项目
 * @property int $day 周期天数
 * @property int $is_device 动态监测[0:人工监测,1:动态监测]
 * @property int $device_num 要求设备数量
 * @property array $type 类型
 * @property string $remark 备注
 */
class Config extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'day', 'is_device', 'device_num'], 'integer'],
            [['type'], 'safe'],
            [['remark'], 'string', 'max' => 255],
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
            'day' => 'Day',
            'is_device' => 'Is Device',
            'device_num' => 'Device Num',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }
}
