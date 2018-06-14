<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Markdown编辑器 <a href="https://github.com/laobubu/HyperMD" target="_blank">HyperMD</a> for Typecho
 * 
 * @package HyperMD
 * @author journey.ad
 * @version 0.1
 * @link https://github.com/journey-ad/HyperMD-Typecho-Plugin
 */
class HyperMD_Plugin implements Typecho_Plugin_Interface
{

    protected static $VERSION = '0.1';
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('admin/write-post.php')->richEditor = array('HyperMD_Plugin', 'Editor');
        Typecho_Plugin::factory('admin/write-page.php')->richEditor = array('HyperMD_Plugin', 'Editor');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {}

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {}

    /**
     * 插入编辑器
     */
    public static function Editor()
    {
        $VERSION = self::$VERSION;
        $hypermdUrl = Helper::options()->pluginUrl.'/HyperMD/lib/hypermd.css?v='.$VERSION;
        $hypermd_lightUrl = Helper::options()->pluginUrl.'/HyperMD/lib/hypermd-light.css?v='.$VERSION;
        $ai1 = Helper::options()->pluginUrl.'/HyperMD/lib/ai1.js?v='.$VERSION;
        $libUrl = Helper::options()->pluginUrl.'/HyperMD/lib/';

        echo <<<EOF
<!-- CodeMirror -->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror/addon/fold/foldgutter.css">

<script src="https://cdn.jsdelivr.net/npm/codemirror/lib/codemirror.js"></script>

<script src="https://cdn.jsdelivr.net/npm/codemirror/addon/fold/foldcode.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror/addon/fold/foldgutter.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror/addon/fold/markdown-fold.js"></script>

<script src="https://cdn.jsdelivr.net/npm/codemirror/addon/mode/overlay.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror/mode/meta.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror/mode/markdown/markdown.js"></script>

<!-- HyperMD -->
<link rel="stylesheet" href="{$hypermdUrl}" />
<link rel="stylesheet" href="{$hypermd_lightUrl}" />

<script type="text/javascript" src="{$ai1}"></script>

<script>
    var textarea = document.getElementById('text')
    var cm = HyperMD.fromTextArea(textarea, {
      hmdModeLoader: "https://cdn.jsdelivr.net/npm/codemirror/",
    })

    cm.setSize(null, "900px") // set height
    cm.focus()
</script>

<!-- Plugin -->
<style>
    div.CodeMirror-wrap{
        clear: both;
    }
    div.h-e-editor {
        float: right;
    }
    span.h-e-menu {
        padding: 5px 5px 0 0;
        font-size: 12px;
        position: relative;
        text-align: center;
        cursor: pointer;
    }
    span.active {
        color: #1e88e5;
        font-weight: 600;
    }
    span.resize{
        display: none;
    }
    .CodeMirror-lines img {
        max-width: 100%;
        max-height: 300px;
    }
    .CodeMirror.cm-s-hypermd-light img.hmd-image.hmd-image-error {
        min-width: 130px;
        min-height: 130px;
        background-size: contain;
    }
</style>
<script>
    $('#text').before('<div class="h-e-editor"><span class="h-e-menu h-e-visual active" onclick="changeView(\'visual\')">可视化</span><span class="h-e-menu h-e-source" onclick="changeView(\'source\')">文本</span></div>');
    changeView = function(x) {
        if(x === 'source'){
            $('#text').text(cm.getValue());
            $('.CodeMirror-wrap').hide();
            $('#text').height($('.CodeMirror-wrap').height());
            $('#text').show();
             $('.resize').css('display', 'block');
            $('.h-e-visual').removeClass('active');
            $('.h-e-source').addClass('active');
        }else{
            $('#text').hide();
            $('.resize').hide();
            $('.CodeMirror-wrap').show();
            cm.setSize(null, $('#text').height());
            cm.setValue($('#text').val());
            $('.h-e-source').removeClass('active');
            $('.h-e-visual').addClass('active');
        }
    }
</script>
EOF;
    }
}
