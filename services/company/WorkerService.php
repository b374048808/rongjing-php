<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-02 20:49:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-02 20:51:01
 * @Description: 
 */

namespace services\company;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\components\Service;
use common\models\company\Worker;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class WorkerService extends Service
{
    /**
     * 记录访问次数
     *
     * @param Member $member
     */
    public function lastLogin(Worker $worker)
    {
        $worker->visit_count += 1;
        $worker->last_time = time();
        $worker->last_ip = Yii::$app->request->getUserIP();
        $worker->save();
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'username');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Worker::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Worker::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByIdWithAssignment($id)
    {
        return Worker::find()
            ->where(['id' => $id])
            ->with('assignment')
            ->one();
    }
}
