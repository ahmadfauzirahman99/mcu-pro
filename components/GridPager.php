<?php
/*
 * @Author: Dicky Ermawan S., S.T., MTA 
 * @Email: wanasaja@gmail.com 
 * @Web: dickyermawan.github.io 
 * @Linkedin: linkedin.com/in/dickyermawan 
 * @Date: 2020-02-19 14:03:03 
 * @Last Modified by: Dicky Ermawan S., S.T., MTA
 * @Last Modified time: 2021-03-18 18:45:11
 */


namespace app\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridPager extends \yii\widgets\LinkPager
{

    public $firstPageLabel = '<span class="mdi mdi-step-backward-2"></span>';
    public $lastPageLabel = '<span class="mdi mdi-step-forward-2"></span>';

    public $prevPageLabel = '<span class="mdi mdi-step-backward"></span>';
    public $nextPageLabel = '<span class="mdi mdi-step-forward"></span>';

    public $options = ['class' => 'pagination pagination-circle justify-content-end'];

    public $linkContainerOptions = ['class' => 'page-item'];

    public $linkOptions = ['class' => 'page-link'];

    public $disabledListItemSubTagOptions = ['class' => 'page-link'];

    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'a');

            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }
}
