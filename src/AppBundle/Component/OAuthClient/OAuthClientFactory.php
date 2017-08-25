<?php

namespace AppBundle\Component\OAuthClient;

use InvalidArgumentException;
use Topxia\Service\Common\ServiceKernel;

class OAuthClientFactory
{
    /**
     * 创建OAuthClient实例.
     *
     * @param string $type   Client的类型
     * @param array  $config 必需包含key, secret两个参数
     *
     * @return AbstractOauthClient
     */
    public static function create($type, array $config)
    {
        if (!array_key_exists('key', $config) || !array_key_exists('secret', $config)) {
            throw new InvalidArgumentException('参数中必需包含key, secret两个为key的值');
        }

        $clients = self::clients();

        if (!array_key_exists($type, $clients)) {
            throw new InvalidArgumentException(array('参数不正确%type%', array('%type%' => $type)));
        }

        $class = $clients[$type]['class'];

        return new $class($config);
    }

    public static function clients()
    {
        $clients = array(
            'weibo' => array(
                'name' => '微博帐号',
                'admin_name' => '微博登录接口',
                'class' => 'AppBundle\Component\OAuthClient\WeiboOAuthClient',
                'icon_class' => 'weibo',
                'icon_color_class' => 'setting-icon__weibo',
                'icon_img' => '',
                'large_icon_img' => 'assets/img/social/weibo.png',
                'key_setting_label' => 'App Key',
                'secret_setting_label' => 'App Secret',
                'apply_url' => 'http://open.weibo.com/authentication/',
            ),
            'qq' => array(
                'name' => 'QQ帐号',
                'admin_name' => 'QQ登录接口',
                'class' => 'AppBundle\Component\OAuthClient\QqOAuthClient',
                'icon_class' => 'qq',
                'icon_color_class' => 'setting-icon__qq',
                'icon_img' => '',
                'large_icon_img' => 'assets/img/social/qq.png',
                'key_setting_label' => 'App ID',
                'secret_setting_label' => 'App Key',
                'apply_url' => 'http://connect.qq.com/',
            ),
            'renren' => array(
                'name' => '人人帐号',
                'admin_name' => '人人登录接口',
                'class' => 'AppBundle\Component\OAuthClient\RenrenOAuthClient',
                'icon_class' => 'renren',
                'icon_color_class' => 'setting-icon__renren',
                'icon_img' => '',
                'large_icon_img' => 'assets/img/social/renren.gif',
                'key_setting_label' => 'App Key',
                'secret_setting_label' => 'App Secret',
                'apply_url' => 'http://dev.renren.com/website',
            ),
            'weixinweb' => array(
                'name' => '微信网页登录接口',
                'admin_name' => '微信网页登录接口',
                'class' => 'AppBundle\Component\OAuthClient\WeixinwebOAuthClient',
                'icon_class' => 'weixin',
                'icon_color_class' => 'setting-icon__weixin',
                'icon_img' => '',
                'large_icon_img' => 'assets/img/social/weixin.png',
                'key_setting_label' => 'App ID',
                'secret_setting_label' => 'App Secret',
                'apply_url' => 'https://open.weixin.qq.com/cgi-bin/frame?t=home/web_tmpl&lang=zh_CN',
            ),
            'weixinmob' => array(
                'name' => '微信内分享登录接口',
                'admin_name' => '微信内分享登录接口',
                'class' => 'AppBundle\Component\OAuthClient\WeixinmobOAuthClient',
                'icon_class' => '',
                'icon_color_class' => '',
                'icon_img' => '',
                'large_icon_img' => '',
                'key_setting_label' => 'App ID',
                'secret_setting_label' => 'App Secret',
                'mp_secret_setting_label' => 'MP文件验证码',
                'apply_url' => 'https://mp.weixin.qq.com/cgi-bin/readtemplate?t=register/step1_tmpl&lang=zh_CN',
            ),
        );

        if (self::getServiceKernel()->hasParameter('oauth2_clients')) {
            $extras = self::getServiceKernel()->getParameter('oauth2_clients');
            $clients = array_merge($clients, $extras);
        }

        return $clients;
    }

    protected static function getServiceKernel()
    {
        return ServiceKernel::instance();
    }
}
