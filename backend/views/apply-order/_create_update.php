<?php
/** @var $this \yii\web\View */

use backend\widgets\SimpleActiveForm;
use common\models\Person;

$form = SimpleActiveForm::begin();
echo $form->field($model, 'reason')->textarea()->label('入库理由');
echo $form->field($model, 'person_id')->dropDownList(Person::findIdName(true))->label('入库申请人');
$html = <<<HTML

    <table border="1" width="400">
    <tr>
        <th>装备名称</th>
        <th>标签号</th>
		<th>数量</th>
    </tr>
    <tr class="number">
        <td>狙击步枪</td>
        <td>
            <input type="hidden" name="ApplyOrder[res][1][containerId]" value="1">
            <input type="hidden" name="ApplyOrder[res][1][resourceId]" value="10">
            <input type="hidden" name="ApplyOrder[res][1][resType]" value="20">
            <input type="text" name="ApplyOrder[res][1][name]">
        </td>
		<td><input type="text" name="ApplyOrder[res][1][quantity]"></td>
    </tr>
     <tr class="number">
        <td>橡皮子弹</td>
        <td>
            <input type="hidden" name="ApplyOrder[res][2][containerId]" value="2">
            <input type="hidden" name="ApplyOrder[res][2][resourceId]" value="9">
            <input type="hidden" name="ApplyOrder[res][2][resType]" value="10">
            <input type="text" name="ApplyOrder[res][2][name]">
        </td>
		<td><input type="text" name="ApplyOrder[res][2][quantity]"></td>
    </tr>
      <tr class="number">
        <td>迫击炮</td>
        <td>
            <input type="hidden" name="ApplyOrder[res][3][containerId]" value="1">
            <input type="hidden" name="ApplyOrder[res][3][resourceId]" value="13">
            <input type="hidden" name="ApplyOrder[res][3][resType]" value="20">
            <input type="text" name="ApplyOrder[res][3][name]">
        </td>
		<td><input type="text" name="ApplyOrder[res][3][quantity]"></td>
    </tr>
</table>
HTML;
echo $html;

echo $form->renderFooterButtons();
SimpleActiveForm::end();