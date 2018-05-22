<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class AlarmCheckWidget extends Widget
{
    private $_alarmContainerId;
    private $_alarmAudioId;

    public function init()
    {
        parent::init();
        $this->_alarmContainerId = 'alarm-container-' . $this->id;
        $this->_alarmAudioId = 'alarm-audio-' . $this->id;
    }

    public function run()
    {
        if (true) {
            $this->registerAssets();
            echo Html::tag('div', '', ['id' => $this->_alarmContainerId]);
            echo Html::tag('audio', '', [
                'src' => Yii::getAlias('@web/audio/alarm.wav'),
                'preload' => 'none',
                'id' => $this->_alarmAudioId
            ]);
        }
    }

    public function registerAssets()
    {
        $alarmRecordUrl = Url::to(['/api/alarm-records']);
        $js = <<<JS
var intervalTime = 2,
    currentTimestamp = Math.round((new Date().getTime()) / 1000),
    lastTimestamp = currentTimestamp - intervalTime,
    nextTimestamp = currentTimestamp;
var audio = $('#{$this->_alarmAudioId}')[0];
function getAlarmRecords() {
  $.get('{$alarmRecordUrl}', {
      start_time: lastTimestamp,
      end_time: nextTimestamp
  }, function(data) {
      if(data && data.length > 0) {
          $('#{$this->_alarmContainerId}').prepend('<div class="alert alert-danger alert-dismissible">' + 
          '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
            data.join('<br>') + 
            '</div>');
          if (audio.paused){
            audio.play();
          }
      }
      lastTimestamp = nextTimestamp;
      nextTimestamp = lastTimestamp + intervalTime;
  }, 'json');
}
setInterval(getAlarmRecords, intervalTime * 1000);
JS;
        $this->view->registerJs($js);
    }
}