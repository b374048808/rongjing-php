<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 11:24:26
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 08:57:58
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '分组列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-map', 'id' => $id], '添加', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'header' => '账号',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return  $model['member']['username'];
                            }
                        ],
                        [
                            'header' => '用户',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return  $model['member']['realname'];
                            }
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete-member', 'member_id' => $model['member_id'], 'step_id' => $model['step_id']]);
                                },
                            ],
                        ],
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>