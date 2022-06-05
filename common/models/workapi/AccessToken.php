<?php

namespace common\models\workapi;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UnauthorizedHttpException;
use common\enums\StatusEnum;
use common\models\company\Worker as Member;
use common\models\common\RateLimit;
use common\models\rbac\AuthAssignment;

/**
 * This is the model class for table "rf_rj_company_worker_api_access_token".
 *
 * @property int $id
 * @property string $refresh_token 刷新令牌
 * @property string $access_token 授权令牌
 * @property int $member_id 用户id
 * @property string $openid 授权对象openid
 * @property string $group 组别
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AccessToken extends \common\models\common\RateLimit
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_rj_company_worker_api_access_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['refresh_token', 'access_token'], 'string', 'max' => 60],
            [['openid'], 'string', 'max' => 50],
            [['group'], 'string', 'max' => 100],
            [['access_token'], 'unique'],
            [['refresh_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'refresh_token' => 'Refresh Token',
            'access_token' => 'Access Token',
            'member_id' => 'Member ID',
            'openid' => 'Openid',
            'group' => 'Group',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return array|mixed|ActiveRecord|\yii\web\IdentityInterface|null
     * @throws UnauthorizedHttpException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // 判断验证token有效性是否开启
        if (Yii::$app->params['user.accessTokenValidity'] === true) {
            $timestamp = (int)substr($token, strrpos($token, '_') + 1);
            $expire = Yii::$app->params['user.accessTokenExpire'];

            // 验证有效期
            if ($timestamp + $expire <= time()) {
                throw new UnauthorizedHttpException('您的登录验证已经过期，请重新登录');
            }
        }

        // 优化版本到缓存读取用户信息 注意需要开启服务层的cache
        $accessToken = Yii::$app->services->projectAccessToken->getTokenToCache($token, $type);

        return $accessToken;
    }

    /**
     * @param $token
     * @param null $group
     * @return AccessToken|\common\models\base\User|null
     */
    public static function findIdentityByRefreshToken($token, $group = null)
    {
        return static::findOne(['group' => $group, 'refresh_token' => $token, 'status' => StatusEnum::ENABLED]);
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * 关联授权角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'member_id'])
            ->where(['app_id' => Yii::$app->id]);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ]
        ];
    }
}
