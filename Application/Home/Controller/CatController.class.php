<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: CatController.class.php
 * User: Timothy Zhang
 * Date: 14-1-23
 * Time: 下午8:00
 */

namespace Home\Controller;

use Common\Logic\CatsLogic;
use Common\Logic\PostsLogic;
use Common\Util\GreenPage;
use Think\Hook;

/**
 * 分类控制器
 * Class CatController
 * @package Home\Controller
 */
class CatController extends HomeBaseController
{

    /**
     * @param null 无实现
     */
    public function index()
    {

    }

    /**
     * 查询指定分类的详细信息
     * @param int $info detail 查询的 id 或者slug
     */
    public function detail($info)
    {
        $CatsLogic = new CatsLogic();
        $PostsLogic = new PostsLogic();
        $cat = $CatsLogic->detail($info);
//        $children = ($Cat->getChildren($cat['cat_id']));
        $this->assign('cat_id', $cat['cat_id']); // 赋值数据集

//
//        if (get_opinion("auto_channel", false, false) && $children['cat_children']) {
//            $this->channel($info);
//            Hook::listen('app_end');
//            die();
//        }

        $this->if404($cat, "非常抱歉，没有这个分类，可能它已经躲起来了"); //优雅的404

        $posts_id = $CatsLogic->getPostsId($cat['cat_id']);


        $count = sizeof($posts_id);
        ($count == 0) ? $res404 = 0 : $res404 = 1;

        if (!empty($posts_id)) {

            $Page = new GreenPage($count, get_opinion('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $posts_list = $PostsLogic->getList($limit, 'single', 'post_date desc', true, array(), $posts_id);

        }


        $this->assign('title', '分类 ' . $cat['cat_name'] . ' 所有文章'); // 赋值数据集
        $this->assign('res404', $res404);
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出
        $this->assign('breadcrumbs', get_breadcrumbs('cats', $cat['cat_id']));

        $this->display('Archive/single-list');

    }


    /**
     * 兼容旧式CMS深目录结构的二级cat结构
     * @param $info
     */
    public function channel($info)
    {
        //TODO 兼容旧式CMS深目录结构的二级cat结构
        $Cat = new CatsLogic();
        $cat = $Cat->detail($info);
        $children = $Cat->getChildren($cat['cat_id']);

        $Cat = new CatsLogic();
        $Posts = new PostsLogic();
        $cat = $Cat->detail($info);

        $this->if404($cat, "非常抱歉，没有这个分类，可能它已经躲起来了"); //优雅的404

        $posts_id = $Cat->getPostsId($cat['cat_id']);
        $count = sizeof($posts_id);
        ($count == 0) ? $res404 = 0 : $res404 = 1;

        if (!empty($posts_id)) {

            $Page = new GreenPage($count, get_opinion('PAGER'));
            $pager_bar = $Page->show();
            $limit = $Page->firstRow . ',' . $Page->listRows;

            $posts_list = $Posts->getList($limit, 'single', 'post_date desc', true, array(), $posts_id);

        }

        $this->assign('children', $children);

        $this->assign('title', $cat['cat_name']); // 赋值数据集
        $this->assign('res404', $res404);
        $this->assign('postslist', $posts_list); // 赋值数据集
        $this->assign('pager', $pager_bar); // 赋值分页输出
        $this->assign('breadcrumbs', get_breadcrumbs('cats', $cat['cat_id']));

        $this->display('Archive/channel-list');


    }


}