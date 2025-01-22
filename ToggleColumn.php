<?php

namespace micheleraso\grid;

use Yii;
use yii\web\View;
use yii\helpers\Html;
use yii\grid\DataColumn;
use luckymax\coreui\widgets\Icon;
use micheleraso\yii\fontawesome\FAS;

/**
 * Class ToggleColumn
 *
 * @package yii2mod\toggle
 */
class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     *
     * @var string
     */
    public $action = 'toggle';

    /**
     * Whether to use ajax or not
     *
     * @var bool
     */
    public $enableAjax = true;

    /**
     * @var string default pk column name
     */
    public $pkColumn = 'primaryKey';

    /**
     * @var string if false, uses FontAwesomeIcons
     */
    public $useCoreuiIcons = false;

    /**
     * @var string icons names
     */
    public $onIcon = 'check';
    public $offIcon = 'times';

    /**
     * @var string icons titles
     */
    public $onTitle = 'Attiva';
    public $offTitle = 'Disattiva';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->enableAjax) {
            $this->registerJs();
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $attribute = $this->attribute;
        $value = $model->$attribute;

        $pkColumn = $this->pkColumn;
        $url = [$this->action, 'id' => $model->$pkColumn, 'attribute' => $attribute];

        if ($value === null || $value == true) {
            $icon = $this->onIcon;
            $title = $this->offTitle;
            $class = 'toggle-column-on';
        } else {
            $icon = $this->offIcon;
            $title = $this->onTitle;
            $class = 'toggle-column-off';
        }

        if ($this->useCoreuiIcons) {
            $class .= ' icon';
        }

        return Html::a(
            $this->useCoreuiIcons ? Icon::render($icon, ['class' => $class]) : FAS::icon($icon, ['class' => $class]),
            $url,
            [
                'title' => $title,
                'class' => 'toggle-column',
                'data-method' => 'post',
                'data-pjax' => '0',
            ]
        );
    }

    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $js = <<< JS
            $("a.toggle-column").on("click", function(e) {
                e.preventDefault();
                $.post($(this).attr("href"), function(data) {
                    var pjaxId = $(e.target).closest(".grid-view").parent().attr("id");
                    if(pjaxId) {
                        $.pjax.reload({container:"#" + pjaxId});
                    } else {
                        window.location.reload(false); 
                    }
                });
                return false;
            });
JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'yii2mod-toggle-column');
    }
}
