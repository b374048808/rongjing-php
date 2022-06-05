<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-06-01 18:02:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-03 10:10:35
 * @Description: 
 */

namespace project\modules\v1\forms;

use common\enums\StatusEnum;
use common\enums\AccessTokenGroupEnum;
use common\models\company\Worker;

/**
 * Class LoginForm
 * @package project\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class LoginForm extends \common\models\forms\LoginForm
{
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'group'], 'required'],
            ['password', 'validatePassword'],
            ['group', 'in', 'range' => AccessTokenGroupEnum::getKeys()]
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '登录帐号',
            'password' => '登录密码',
            'group' => '组别',
        ];
    }

    /**
     * 用户登录
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            // email 登录
            if (strpos($this->username, "@")) {
                $this->_user = Worker::findOne(['email' => $this->username, 'status' => StatusEnum::ENABLED]);
            } else {
                $this->_user = Worker::findByUsername($this->username);
            }
        }

        return $this->_user;
    }
}
