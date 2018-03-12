<?php

namespace backend\controllers;

use backend\components\AuthWebController;
use backend\components\MessageAlert;
use common\models\Settings;
use Yii;
use yii\base\Model;

class SettingsController extends AuthWebController
{
    public function actionIndex()
    {
        $this->rememberUrl();

        $settings = Settings::find()->indexBy('key')->all();
        if (Model::loadMultiple($settings, Yii::$app->request->post()) && Model::validateMultiple($settings)) {

            foreach ($settings as $setting) {
                if ($setting->oldAttributes['value'] != $setting->value) {
                    $setting->save(false);
                }
            }
            MessageAlert::set(['success' => '修改成功']);
            return $this->actionPreviousRedirect();
        }
        return $this->render('index', [
            'settings' => $settings
        ]);
    }
}