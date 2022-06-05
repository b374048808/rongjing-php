<?php

namespace project\modules\v1\controllers\member;

use project\controllers\UserAuthController;
use common\models\member\Invoice;

/**
 * 发票管理
 *
 * Class InvoiceController
 * @package project\modules\v1\controllers\member
 * @author jianyan74 <751393839@qq.com>
 */
class InvoiceController extends UserAuthController
{
    /**
     * @var Invoice
     */
    public $modelClass = Invoice::class;
}
