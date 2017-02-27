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
 * Class PluploadAsset
 * @package xutl\plupload
 */
class PluploadAsset extends AssetBundle
{
    public $sourcePath = '@xutl/plupload/assets';

    /**
     * @var array 包含的JS
     */
    public $js = [
        'plupload.full.min.js',
    ];

    /**
     * @var array 定义依赖
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}