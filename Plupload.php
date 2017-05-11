<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\plupload;


use Yii;
use yii\base\Widget;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Wrapper for Plupload
 * A multiple file upload utility using Flash, Silverlight, Google Gears, HTML5 or BrowserPlus.
 * @url http://www.plupload.com/
 * @version 1.0
 * @author Bound State Software
 */
class Plupload extends Widget
{
    /**
     * Page URL or action to where the files will be uploaded to.
     * @var mixed
     */
    public $url;

    public $htmlOptions = [];

    /**
     * The label to display on the browse link.
     * @var string
     */
    public $browseLabel = 'Select Files';

    /**
     * HTML options for the browse link.
     * @var array
     */
    public $browseOptions = [];

    /**
     * ID of the error container.
     * @var string
     */
    public $errorContainer;

    /**
     * Options to pass directly to the JavaScript plugin.
     * Please refer to the Plupload documentation:
     * @link http://www.plupload.com/documentation.php
     * @var array
     */
    public $options = [];

    /**
     * @var string language
     */
    public $language;

    public $autoUpload = false;

    /**
     * The JavaScript event callbacks to attach to Plupload object.
     * @link http://www.plupload.com/example_events.php
     * In addition to the standard events, this widget adds a "FileSuccess"
     * event that is fired when a file is uploaded without error.
     * NOTE: events signatures should all have a first argument for event, in
     * addition to the arguments documented on the Plupload website.
     * @var array
     */
    public $events = [];

    /**
     * @return int the max upload size in MB
     */
    public static function getPHPMaxUploadSize()
    {
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        return min($max_upload, $max_post, $memory_limit);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->registerTranslations();
        // Make sure URL is provided
        if (empty($this->url))
            throw new Exception(Yii::t('yii', '{class} must specify "url" property value.', array('{class}' => get_class($this))));
        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();
        $id = $this->htmlOptions['id'];

        if (!isset($this->browseOptions['id']))
            $this->browseOptions['id'] = "{$id}_browse";

        if (!isset($this->browseOptions['class']))
            $this->browseOptions['class'] = "btn btn-success";

        if (!isset($this->errorContainer))
            $this->errorContainer = "{$id}_em";
        if (!isset($this->options['multipart_params']))
            $this->options['multipart_params'] = [];
        $this->options['multipart_params'][Yii::$app->request->csrfParam] = Yii::$app->request->csrfToken;
        $bundle = PluploadAsset::register($this->view);

        $language = $this->language ? $this->language : Yii::$app->language;
        $languageBundle = PluploadLanguageAsset::register($this->view);
        $languageBundle->language = $language;

        $defaultOptions = [
            'browse_button' => $this->browseOptions['id'],
            'url' => Url::to($this->url),
            'container' => $id,
            'runtimes' => 'gears,html5,flash,silverlight,browserplus',
            'flash_swf_url' => "{$bundle->baseUrl}/Moxie.swf",
            'silverlight_xap_url' => "{$bundle->baseUrl}/Moxie.xap",
            'max_file_size' => static::getPHPMaxUploadSize() . 'mb',
            'error_container' => "#{$this->errorContainer}",
        ];
        $options = ArrayHelper::merge($defaultOptions, $this->options);
        $options = Json::encode($options);

        // Output
        echo Html::beginTag('div', $this->htmlOptions);
        echo Html::a($this->browseLabel, '#', $this->browseOptions);
        echo Html::endTag('div');


        // Generate event JavaScript
        $events = '';
        foreach ($this->events as $event => $callback) {
            $events .= "{$this->id}.bind('$event', $callback);\n";
        }
        //开启自动上传
        if ($this->autoUpload) {
            $autoUploadcallback = new JsExpression("function(uploader, files){jQuery(\"#{$this->errorContainer}\").hide();jQuery(\"#{$this->browseOptions['id']}\").button(\"loading\");uploader.start();}");
            $events .= "{$this->id}.bind('FilesAdded', $autoUploadcallback);\n";
        }
        $this->view->registerJs("var {$this->id} = new plupload.Uploader($options);\n{$this->id}.init();\n$events");
    }

    /**
     * 获取语言包
     * @param string $message
     * @param array $params
     * @return string
     */
    public static function t($message, $params = [])
    {
        return Yii::t('xutl/plupload/plupload', $message, $params);
    }

    /**
     * 注册语言包
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['xutl/plupload/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@xutl/plupload/messages',
            'fileMap' => [
                'xutl/plupload/plupload' => 'plupload.php',
            ],
        ];
    }
}