<?php

namespace Addons\baidushare;
use Common\Controller\Addon;

/**
 * 百度分享插件
 * @author xjh1994
 */

class BaidushareAddon extends Addon
{

    // public $custom_config = 'config';

    /**
     * @var array $info 描述信息
     */
    public $info = array(
        'name'        => 'Baidushare',
        'title'       => '百度分享',
        'description' => '用户将网站内容分享到第三方网站，第三方网站的用户点击专有的分享链接，从第三方网站带来社会化流量。',
        'status'      => 1,
        'author'      => 'xjh1994',
        'version'     => '0.1'
    );

    /**
     * @function install 安装
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * @function uninstall 卸载
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    //实现的documentDetailAfter钩子方法
    /**
     * @function documentDetailAfter 实现的documentDetailAfter钩子方法
     * @param $param
     */
    public function documentDetailAfter($param)
    {
        $this->assign('addons_config', $this->getConfig());
        $this->display('share');
    }

}