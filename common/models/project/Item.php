<?php

namespace common\models\project;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\helpers\RegularHelper;
use common\models\company\Worker;
use Yii;

/**
 * This is the model class for table "rf_rj_project_item".
 *
 * @property int $id
 * @property string $title 项目名称
 * @property int $type 项目类型
 * @property string $entrust 委托方
 * @property string $belonger 归属人
 * @property string $contact 联系人
 * @property string $mobile 联系方式
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $address 默认地址
 * @property int $end_time 结束时间
 * @property int $start_time 开始时间
 * @property string $demand 项目需求
 * @property string $survey 项目概况
 * @property array $file 附件
 * @property int $user_id 创建员工ID
 * @property string $money 金额
 * @property string $number 编号
 * @property int $struct_type 结构类型
 * @property int $event_time 立项时间
 * @property array $images 附件照片
 * @property string $steps_name 步骤NAME
 * @property int $sort 优先级
 * @property int $verify 审核状态
 * @property string $collect_money 收款金额
 * @property array $collection 收款凭证
 * @property string $description 描述
 * @property string $remark 备注
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Item extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['type', 'province_id', 'city_id', 'area_id', 'user_id', 'struct_type', 'sort', 'verify', 'status', 'created_at', 'updated_at'], 'integer'],
            [['start_time', 'end_time', 'event_time', 'file', 'images', 'collection'], 'safe'],
            [['money', 'collect_money'], 'number'],
            [['title', 'number', 'steps_name'], 'string', 'max' => 50],
            [['entrust', 'demand', 'survey', 'description'], 'string', 'max' => 140],
            [['belonger', 'contact'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 255],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '不是一个有效的手机号码'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'type' => 'Type',
            'entrust' => 'Entrust',
            'belonger' => 'Belonger',
            'contact' => 'Contact',
            'mobile' => 'Mobile',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'address' => 'Address',
            'end_time' => 'End Time',
            'start_time' => 'Start Time',
            'demand' => 'Demand',
            'survey' => 'Survey',
            'file' => 'File',
            'user_id' => 'User ID',
            'money' => 'Money',
            'number' => 'Number',
            'struct_type' => 'Struct Type',
            'event_time' => 'Event Time',
            'images' => 'Images',
            'steps_name' => 'Steps Name',
            'sort' => 'Sort',
            'verify' => 'Verify',
            'collect_money' => 'Collect Money',
            'collection' => 'Collection',
            'description' => 'Description',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($models);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    public function getStepsLog()
    {
        return $this->hasMany(StepsLog::class, ['pid' => 'id']);
    }

    // 日志
    public function getNewStepsLog()
    {
        return $this->hasOne(StepsLog::class, ['pid' => 'id'])->orderBy('id desc');
    }

    // 额外配置
    public function getConfig()
    {
        return $this->hasOne(Config::class, ['pid' => 'id']);
    }

    // 关联用户
    public function getUser()
    {
        return $this->hasOne(Worker::class, ['id' => 'user_id']);
    }

    // 项目编号
    public function getChildNumber()
    {
        return $this->hasMany(Number::class, ['pid' => 'id']);
    }

    // 关联审核记录
    public function getVerifyLog()
    {
        return $this->hasMany(Log::class, ['map_id' => 'id'])->andWhere(['action' => Log::ACTION_VERIFY]);
    }


    // 关联合同

    public function getContracts()
    {
        return $this->hasMany(Contract::class, ['pid' => 'id'])
            ->andWhere(['status' => StatusEnum::ENABLED]);
    }

    // 最新日志
    public function getNewVerifyLog()
    {
        return $this->hasOne(Log::class, ['map_id' => 'id'])->with(['member' => function ($queue) {
            $queue->select(['id', 'realname']);
        }])->andWhere(['action' => Log::ACTION_VERIFY])->orderBy('id desc');
    }

    // 关联任务
    public function getServices()
    {
        return $this->hasMany(Service::class, ['pid' => 'id']);
    }
}
