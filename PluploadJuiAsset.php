<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\plupload;

use Yii;
use yii\web\AssetBundle;

/**
 * Class PluploadJuiAsset
 * @package xutl\plupload
 */
class PluploadJuiAsset extends AssetBundle
{
    public $sourcePath = '@vendor/xutl/yii2-plupload-widget/assets';

    /**
     * @var array 依赖的CSS
     */
    public $css = [
        'jquery.ui.plupload/css/jquery.ui.plupload.css'
    ];

    /**
     * @var array 包含的JS
     */
    public $js = [
        'jquery.ui.plupload/jquery.ui.plupload.js',
    ];

    /**
     * @var array 定义依赖
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'xutl\plupload\PluploadAsset'
    ];
}