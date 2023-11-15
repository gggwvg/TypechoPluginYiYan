<?php

namespace TypechoPlugin\YiYan;

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Select;
use Typecho\Widget\Helper\Form\Element\Text;
use Widget\Options;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

const JINRISHICI = 'jinrishici';
const HITOKOTO = 'hitokoto';

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
        $type = new Select('type', array(
            HITOKOTO => '一言',
            JINRISHICI => '今日诗词',
        ), HITOKOTO, _t('一言类型'));
        $form->addInput($type);

        $params = new Text(
            'params',
            null,
            null,
            _t('URL 参数，如：c=i&token=xxx'),
            _t('URL 参数根据情况选填。参数详情见：<a href="https://developer.hitokoto.cn/sentence/#%E8%AF%B7%E6%B1%82%E5%8F%82%E6%95%B0">一言</a>、<a href="https://www.jinrishici.com/doc/">今日诗词</a>')
        );
        $form->addInput($params);
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
        $message = '404: Not Found';
        $params = Options::alloc()->plugin('YiYan')->params;
        if (!empty($params)) {
            $params = '?' . trim($params, "?");
        }

        $type = Options::alloc()->plugin('YiYan')->type;
        if ($type == JINRISHICI) {
            $api = 'https://v2.jinrishici.com/one.json' . $params;
            $data = json_decode(file_get_contents($api), true);
            $message = $data['data']['content'] . ' ——' . $data['data']['origin']['title'];
        } else if ($type == HITOKOTO) {
            $api = "https://v1.hitokoto.cn" . $params;
            $data = json_decode(file_get_contents($api), true);
            $message = $data['hitokoto'] . ' ——' . $data['from'];
        }
        echo '<span class="message success">' . htmlspecialchars($message) . '</span>';
    }
}
