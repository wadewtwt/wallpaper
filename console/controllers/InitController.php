<?php

namespace console\controllers;

use common\models\Admin;
use common\models\Settings;
use common\models\User;
use yii\console\Controller;

class InitController extends Controller
{
    public function actionInitData()
    {
        //$this->initAdmin();
        //$this->initUser();
    }

    public function actionInitSettings()
    {
        $data = [
            Settings::KEY_RK_OPTIONS => "入库",
            Settings::KEY_CK_OPTIONS => "出库",
            Settings::KEY_SL_OPTIONS => "申领",
            Settings::KEY_GH_OPTIONS => "归还",
        ];
        foreach ($data as $key => $value) {
            $model = Settings::findOne(['key' => $key]);
            if (!$model) {
                $model = new Settings();
                $model->key = $key;
                $model->value = $value;
                $model->save(false);
            }
        }
    }

    protected function initAdmin()
    {
        $model = new Admin();
        $model->id = Admin::SUPER_ADMIN_ID;
        $model->username = 'admin';
        $model->setPassword(123456);
        $model->cellphone = '12345678910';
        $model->generateAuthKey();
        $model->name = '超级管理员';
        $model->save();
    }

    protected function initUser()
    {
        $model = new User();
        $model->cellphone = '12345678910';
        $model->setPassword(123456);
        $model->generateAuthKey();
        $model->name = '测试人员';
        $model->save();
    }
}