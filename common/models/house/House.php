<?php

namespace common\models\house;

use Yii;

/**
 * This is the model class for table "rf_rj_house_list".
 *
 * @property int $id
 * @property string $title 户主（单位信息）
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $cover 封面
 * @property string $mobile 手机号码
 * @property string $address 默认地址
 * @property int $year 建筑年份
 * @property string $area 面积
 * @property int $nature 房屋性质
 * @property int $layer 结构层数
 * @property string $news 房屋朝向
 * @property string $type 结构类型
 * @property string $roof 屋面形式
 * @property double $lng 经度
 * @property double $lat 纬度
 * @property array $layout_cover 建筑物图
 * @property array $plan_cover 平面图
 * @property int $floor 楼板形式
 * @property int $wall 墙体形式
 * @property int $basement 地下室
 * @property int $beam 圈梁
 * @property int $column 构造柱
 * @property string $side 周边环境
 * @property string $property_nature 产权性质
 * @property int $room 间   数
 * @property string $base_form 基础形式
 * @property string $history 历史情况
 * @property int $user_id 录入人员id
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class House extends \common\models\base\BaseModel
{

    public $lnglat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_house_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            [['user_id', 'room', 'province_id', 'city_id', 'area_id', 'year', 'nature', 'layer', 'floor', 'wall', 'basement', 'beam', 'column', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['area', 'lng', 'lat'], 'number'],
            [['layout_cover', 'plan_cover'], 'safe'],
            [['title', 'side', 'property_nature', 'base_form', 'use_change', 'disasters', 'detect_scope', 'property_card', 'land_card', 'roof', 'type'], 'string', 'max' => 50],
            [['cover', 'address'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 20],
            [['news'], 'string', 'max' => 10],
            [['description', 'history'], 'string', 'max' => 140],
            ['lnglat', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '姓名(单位名称)',
            'province_id' => '省',
            'city_id' => '城市',
            'area_id' => '镇',
            'cover' => '封面',
            'mobile' => '手机号码',
            'address' => '地址',
            'year' => '年',
            'area' => '面积',
            'nature' => '性质',
            'layer' => '层数',
            'news' => '朝向',
            'type' => '结构类型',
            'roof' => '屋面形式',
            'lng' => '经度',
            'lat' => 'L纬度',
            'layout_cover' => '建筑物图',
            'plan_cover' => '平面图',
            'floor' => '楼板形式',
            'wall' => '墙体',
            'basement' => '地下室',
            'beam' => '圈梁',
            'column' => '构造柱',
            'side' => '周边环境',
            'property_nature' => '产权性质',
            'room' => '间   数',
            'base_form' => '基础形式',
            'history' => '历史情况',
            'user_id' => '创建人ID',
            'description' => '备注',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = $this->user_id ?: Yii::$app->user->id;
        }

        if (!empty($this->lnglat)) {

            // $lnglat =Map::bd_decrypt($this->lnglat['lng'],$this->lnglat['lat']);
            $this->lng = $this->lnglat['lng'];
            $this->lat = $this->lnglat['lat'];
        }

        return parent::beforeSave($insert);
    }
}
