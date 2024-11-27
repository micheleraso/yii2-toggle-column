# Toggle data column for Yii2

Provides a toggle data column and action

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist micheleraso/yii2-toggle-column "*"
```

or add

```
"micheleraso/yii2-toggle-column": "*"
```

to the require section of your `composer.json` file.

## Usage

Once the extension is installed, simply use it in your code by :

```php
// Controller
use micheleraso\grid\actions\ToggleAction;

public function actions()
{
    return [
        'toggle-status' => [
            'class' => ToggleAction::className(),
            'onValue' => User::STATUS_ACTIVE,
            'offValue' => User::STATUS_NOT_ACTIVE,
            'modelClass' => 'common\models\User',
            'attribute' => 'status',
            'setFlash' => true,
        ]
    ];
}

// Pjax::begin();
GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        [
           'class' => '\micheleraso\grid\ToggleColumn',
            'attribute' => 'status',
            'action' => 'toggle-status',
            'onText' => 'Disabilitato',
            'offText' => 'Abilitato',
            'displayValueText' => true,
            'onValueText' => 'Disabilitato',
            'offValueText' => 'Abilitato',
            'iconOn' => 'stop',
            'iconOff' => 'stop',
            'enableAjax' => false, // Imposta a true quando si usa pjax
            'confirm' => function($model, $toggle) {
                if ($model->status == Member::STATUS_NOT_ACTIVE) {
                    return "Confermi di abilitare: {$model->username}({$model->id})?";
                } else {
                    return "Confermi di disabilitare: {$model->username}({$model->id})?";
                }
            },
            'headerOptions' => array('style' => 'width:80px;'),
        ],
    ],
]);

// Pjax::end();
```
