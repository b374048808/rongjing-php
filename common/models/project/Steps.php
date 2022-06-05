<?php

namespace common\models\project;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "rf_rj_project_item_steps".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $name 标识
 * @property string $description 描述
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Steps extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_project_item_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'name'], 'string', 'max' => 50],
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
            'title' => 'Title',
            'name' => 'Name',
            'description' => 'Description',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * 上一篇
     *
     * @param int $id 当前文章id
     * @return false|null|string
     */
    public static function getPrev($title)
    {
        $model = self::findOne(['name' => $title]);
        return self::find()
            ->where([
                'or',
                ['<', 'sort', $model->sort],
                ['<', 'id', $model->id]
            ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy(['sort' => SORT_DESC, 'id' => SORT_DESC])
            ->one();
    }

    /**
     * 下一篇
     * scalar
     * @param int $id 当前文章id
     * @return false|null|string
     */
    public static function getNext($title)
    {
        $model = self::findOne(['name' => $title]);
        return self::find()
            ->where([
                'or',
                ['>', 'sort', $model->sort],
                ['>', 'id', $model->id]
            ])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc', 'id asc')
            ->one();
    }


    public static function getTitle($id)
    {
        $model = self::findOne($id);
        return $model ? $model['title'] : '';
    }


    public static function getNameTitle($name)
    {
        $model = self::findOne(['name' => $name]);
        return $model ? $model['title'] : '';
    }

    public static function getMap()
    {
        $model = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::map($model, 'id', 'title');
    }
}
