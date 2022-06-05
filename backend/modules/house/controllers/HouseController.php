<?php

namespace backend\modules\house\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\house\House;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = House::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'partialMatchAttributes' => ['title'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);
        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }

    /**
     * 编辑/创建
     * @param number id
     * @return mixed|array
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {

            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        // 不属于新建时，转化坐标
        if (!$model->isNewRecord) {
            $model->lnglat['lng'] = $model->lng;
            $model->lnglat['lat'] = $model->lat;
        }

        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }

    /**
     * 回收站
     * 
     * @throws: 
     */
    public function actionRecycle()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'partialMatchAttributes' => ['title'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['=', 'status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 还原
     * 
     * @param int
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = House::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }


    /**
     * 下载
     */
    public function actionDownload()
    {
        $file = 'house-default.xls';

        $path = Yii::getAlias('@backend') . '/modules/monitor/file/' . $file;

        Yii::$app->response->sendFile($path, '权限数据_' . time() . '.xls');
    }
}
