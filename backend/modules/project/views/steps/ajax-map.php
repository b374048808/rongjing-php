<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 11:28:07
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-06-04 08:53:52
 * @Description: 
 */

use common\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <?php Pjax::begin(); ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>账号</th>
                <th>真实姓名</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($models as $model) { ?>
                <tr id=<?= $model->id; ?>>
                    <td><?= $model['username'] ?></td>
                    <td><?= $model['realname'] ?></td>
                    <td>
                        <?= Html::a('添加', ['/project/steps/add-member', 'step_id' => $id, 'member_id' => $model['id']]) ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?= LinkPager::widget([
        'pagination' => $pages
    ]); ?>
    <?php Pjax::end(); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>