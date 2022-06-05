<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-16 16:19:51
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 08:58:05
 * @Description: 
 */

namespace backend\modules\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\company\Worker;
use common\models\project\Steps;
use common\models\project\StepsMember;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class StepsController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Steps::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', null);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'sort' => SORT_ASC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['pid' => $pid]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    public function actionMap($id)
    {
        $request = Yii::$app->request;

        $query = StepsMember::find()
            ->andWhere(['step_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxMap($id)
    {

        $data = Worker::find()
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['not in', 'id', StepsMember::getMemberColumn($id)])
            ->orderBy('id desc');

        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)->limit($pages->limit)->all();

        return $this->renderAjax($this->action->id, [
            'models' => $models,
            'pages' => $pages,
            'id' => $id
        ]);
    }


    public function actionAddMember()
    {
        $request = Yii::$app->request;

        $model = new StepsMember();
        $model->load($request->get(), '');
        return $model->save()
            ? $this->redirect(['map', 'id' => $request->get('step_id')])
            : $this->message($this->getError($model), $this->redirect(['map', 'id' => $request->get('step_id')]), 'error');
    }


    public function actionDeleteMember($step_id, $member_id)
    {
        return StepsMember::deleteAll(['step_id' => $step_id, 'member_id' => $member_id])
            ? $this->redirect(Yii::$app->request->referrer)
            : $this->message('删除失败', $this->redirect(Yii::$app->request->referrer), 'error');
    }
}
