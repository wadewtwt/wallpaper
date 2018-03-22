<?php
/* @var $this \yii\web\View */

use common\models\base\ConfigString;
use yii\helpers\Html;

/** @var $admin \common\models\Admin */
$admin = Yii::$app->user->identity;
?>

<header class="main-header">

    <?= Html::a(
        '<span class="logo-mini">' . Yii::$app->name . '</span>' .
        '<span class="logo-lg">' . Html::img('@web/images/logo-white.png', ['alt' => Yii::$app->name]) . '</span>'
        , Yii::$app->homeUrl, ['class' => 'logo']
    ) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <a href="javascript:history.back()" class="return-toggle" role="button">
            <span class="sr-only">返回</span>&nbsp;返回
        </a>

        <button class="btn btn-warning header-button" id="clear_alarm">消音</button>
        <?php
        $clearAlarmUrl = Yii::$app->params[ConfigString::PARAMS_CLEAR_ALARM];
        $js = <<<JS
$('#clear_alarm').click(function() {
    $.get('{$clearAlarmUrl}');
});
JS;
        $this->registerJs($js);
        ?>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="header-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span class="hidden-xs">&nbsp;<?= $admin->name ?></span>
                    </a>
                    <div class="dropdown-menu">
                        <?= Html::a((\kriss\iframeLayout\IframeModeChangeAction::isIframeMode() ? '关闭' : '开启') . '标签页模式',
                            ['/site/iframe-mode-change'], [
                                'data-method' => 'post',
                                'class' => 'dropdown-menu-item'
                            ]) ?>
                        <?= Html::a('修改密码', ['/account/modify-password'], [
                            'class' => 'dropdown-menu-item'
                        ]) ?>
                        <?= Html::a('退出登录', ['/site/logout'], [
                            'data-method' => 'post',
                            'class' => 'dropdown-menu-item'
                        ]) ?>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>