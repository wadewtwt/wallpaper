<?php

namespace frontend\controllers;

use common\models\Paper;
use yii\rest\ActiveController;
use QL\QueryList;

class BookController extends ActiveController
{
    public $modelClass = 'frontend\models\Paper';

    public function actionSendEmail()
    {
        $data['name'] = '我是大王屯';
        $data['age'] = 18;
        $data['sex'] = '男';
        return $data;
    }

    public function actionCollectPic()
    {

        $html = file_get_contents('http://www.win4000.com/zt/weimei.html');

        $ql = QueryList::html($html);

        $hrefs = $ql->rules([
            'imgHref' => ['.Left_list_cont1>.tab_tj>.tab_box>div>ul>li>a','href']
        ])
            ->query()
            ->getData();

        $model = new Paper();
        foreach ($hrefs->all() as $k=>$v){

            $html2 = file_get_contents($v['imgHref']);
            $ql2 = QueryList::html($html2);
            $bigPicHref = $ql2->find('.pic-meinv>a>img')->src;
            $picTitle = $ql2->find('.pic-meinv>a>img')->alt;
            $picIntroduction = $ql2->find('.npaper_jj>p')->html();

            $model['title'] = $picTitle;
            $model['url'] = $bigPicHref;
            $model['introduction'] = $picIntroduction;

            $_model=clone $model;
            $_model->setAttributes($model);
            $_model->save();

        }





    }

}