<?php

use common\enums\monitor\WarnTypeEnum;
use yii\widgets\ActiveForm;
use common\widgets\webuploader\Files;
use common\enums\StructEnum;
?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='col-sm-4 text-right'>{label}</div><div class='col-sm-8'>{input}{hint}{error}</div>",
        ]
    ]); ?>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-md-8 col-lg-6">
                        <div class="box">
                            <div class="box-body">
                                <?= $form->field($model, 'title')->textInput() ?>
                                <?= $form->field($model, 'mobile')->textInput() ?>
                                <?= $form->field($model, 'address')->textInput() ?>
                                <?= \common\widgets\provinces\Provinces::widget([
                                    'form' => $form,
                                    'model' => $model,
                                    'provincesName' => 'province_id', // 省字段名
                                    'cityName' => 'city_id', // 市字段名
                                    'areaName' => 'area_id', // 区字段名
                                    // 'template' => 'short' //合并为一行显示
                                ]); ?>
                                <hr />
                                <h4>建筑物概况</h4>
                                <?= $form->field($model, 'year')->textInput() ?>
                                <?= $form->field($model, 'area')->textInput()->hint('单位:平方') ?>
                                <?= $form->field($model, 'layer')->textInput() ?>
                                <?= $form->field($model, 'lnglat')->widget(\common\widgets\selectmap\Map::class, [
                                    'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
                                ]); ?>
                                <?= $form->field($model, 'news')->textInput() ?>
                                <hr />
                                <h4>结构概况</h4>
                                <?= $form->field($model, 'type')->radioList(StructEnum::typeMap()); ?>
                                <?= $form->field($model, 'roof')->dropDownList(StructEnum::roofMap()); ?>
                                <?= $form->field($model, 'floor')->dropDownList(StructEnum::roofMap()); ?>
                                <?= $form->field($model, 'basement')->radioList(['0' => '无', '1' => '有']); ?>
                                <?= $form->field($model, 'beam')->radioList(['0' => '无', '1' => '有']); ?>
                                <?= $form->field($model, 'column')->radioList(['0' => '无', '1' => '有']); ?>
                                <?= $form->field($model, 'description')->textarea() ?>
                                <?= $form->field($model, 'sort')->textInput() ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">建筑物图片</h3>
                            </div>
                            <div class="box-body">
                                <?= $form->field($model, 'cover')->widget(Files::class, [
                                    'config' => [
                                        // 可设置自己的上传地址, 不设置则默认地址
                                        // 'server' => '',
                                        'pick' => [
                                            'multiple' => false,
                                        ],
                                        'formData' => [
                                            // 不配置则不生成缩略图
                                            'thumb' => [
                                                [
                                                    'width' => 100,
                                                    'height' => 100,
                                                ],
                                                [
                                                    'width' => 200,
                                                    'height' => 200,
                                                ],
                                            ],
                                            'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
                                        ],
                                    ]
                                ]); ?>

                                <?= $form->field($model, 'layout_cover')->widget(common\widgets\webuploader\Files::class, [
                                    'config' => [
                                        'pick' => [
                                            'multiple' => true,
                                        ],
                                        'formData' => [
                                            // 不配置则不生成缩略图
                                            'thumb' => [
                                                [
                                                    'width' => 100,
                                                    'height' => 100,
                                                ],
                                                [
                                                    'width' => 200,
                                                    'height' => 200,
                                                ],
                                            ],
                                            'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
                                        ],
                                    ],
                                ]); ?>
                                <?= $form->field($model, 'plan_cover')->widget(common\widgets\webuploader\Files::class, [
                                    'config' => [
                                        'pick' => [
                                            'multiple' => true,
                                        ],
                                        'formData' => [
                                            // 不配置则不生成缩略图
                                            'thumb' => [
                                                [
                                                    'width' => 100,
                                                    'height' => 100,
                                                ],
                                                [
                                                    'width' => 200,
                                                    'height' => 200,
                                                ],
                                            ],
                                            'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
                                        ],
                                    ],
                                ]); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>