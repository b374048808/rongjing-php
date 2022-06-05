<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 17:49:59
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 14:05:12
 * @Description: 
 */

namespace common\models\project;

use common\models\company\Worker;
use Yii;

/**
 * This is the model class for table "rf_rj_project_service".
 *
 * @property int $id
 * @property int $pid 项目ID
 * @property int $cate_id 分类id
 * @property int $manager 负责人
 * @property string $contact 联系人
 * @property string $mobile 联系方式
 * @property int $end_time 结束时间
 * @property int $start_time 开始时间
 * @property int $user_id 发布者
 * @property array $members 参与人员
 * @property string $description 描述
 * @property int $audit 状态
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Service extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'cate_id', 'manager', 'user_id', 'audit', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['end_time', 'start_time'], 'number'],
            [['manager'], 'required'],
            [['members'], 'safe'],
            [['contact'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 20],
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
            'cate_id' => 'Cate ID',
            'manager' => 'Manager',
            'contact' => 'Contact',
            'mobile' => 'Mobile',
            'end_time' => 'End Time',
            'start_time' => 'Start Time',
            'user_id' => 'User ID',
            'members' => 'Members',
            'description' => 'Description',
            'audit' => 'Audit',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 发布者
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Worker::class, ['id' => 'user_id']);
    }


    public function getMember()
    {
        return $this->hasOne(Worker::class, ['id' => 'manager']);
    }


    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'pid']);
    }


    /**
     * 关联项目审核信息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewVerifyLog()
    {
        return $this->hasOne(Log::class, ['map_id' => 'id'])->andWhere(['action' => Log::ACTION_VERIFY])->orderBy('id desc')
            ->viaTable(Item::tableName(), ['id' => 'pid']);
    }
}
