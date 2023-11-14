<?php

namespace TypechoPlugin\YiYan;

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * 每次刷新管理后台页面，都在页面顶部随机显示一句话。
 *
 * @package 一言
 * @author gggwvg
 * @version 1.0.0
 * @link https://jian.wang
 */
class Plugin implements PluginInterface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     */
    public static function activate()
    {
        \Typecho\Plugin::factory('admin/menu.php')->navBar = __CLASS__ . '::render';
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     */
    public static function deactivate()
    {
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form)
    {
    }

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render()
    {
        $api = "https://v1.hitokoto.cn";
        $data = json_decode(file_get_contents($api), true);
        $message = $data['hitokoto'] . ' —— ' . $data['from'];
        echo '<span class="message success">' . htmlspecialchars($message) . '</span>';
    }
}
