<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 10:18
 */

namespace app\admin\controller;


use app\common\model\Goodslibrarycate;
use app\common\model\SixunOpera;

class Category extends BaseController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Goodslibrarycate();
        parent::__construct();
    }

    /**
     * 分类列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function index()
    {
        $categoryId = input('categoryId') ?: 0;
        $cateList = $this->categoryModel->getCategory($categoryId);
        $this->assign('cateList', $cateList);
        if ($categoryId) {
            $this->assign('parentId', $categoryId);
            return $this->fetch('categoryChild');
        }
        return $this->fetch();
    }

    /**
     * 手动添加分类
     * @return mixed
     */
    public function categoryAdd()
    {
        if (request()->isAjax()) {
            addAdminOperaLog();
            $data = input('post.');
            $check = $this->validate($data, 'CategoryValidate');
            if (true !== $check) {
                exit_json(-1, $check);
            }
            $res = $this->categoryModel->allowField(true)->save($data);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '保存失败');
            }
        }
        $parentId = input('parentId');
        $this->assign('pid', $parentId);
        return $this->fetch();
    }

    /**
     * 分类编辑
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function categoryEdit()
    {
        if (request()->isAjax()) {
            addAdminOperaLog();
            $data = input('post.');
            $res = $this->categoryModel->allowField(true)->save($data, ['id'=>$data['id']]);
            if($res){
                exit_json();
            }else{
                exit_json(-1, '保存失败');
            }
        }
        $cateId = input('categoryId');
        $cate = $this->categoryModel->where('id', 'eq', $cateId)->find();
        $this->assign('cate', $cate);
        return $this->fetch();
    }

    /**
     * 保存分类图片
     */
    function addCategoryImg()
    {
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(__UPLOAD__ . '/category', md5(microtime() . rand(1000, 9999)));
            if ($info) {
                $saveName = $info->getSaveName();
                $path = "/upload/category/" . $saveName;
                $img = "<input type='hidden' name='image' value='" . $path . "'/>";
                exit_json(1, '操作成功', $img);
            } else {
                // 上传失败获取错误信息
                exit_json(-1, $file->getError());
            }
        }
        exit_json();
    }

    /**
     * 删除分类
     */
    function delCate()
    {
        addAdminOperaLog();
        $ids = input('post.ids');
        $res = $this->categoryModel->delCate($ids);
        if($res){
            exit_json();
        }else{
            exit_json(-1);
        }

    }


    /**
     * 同步思迅分类
     * 分类包括两级分类，一级不显示，只是用于上架商品时用
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function asyncCategory()
    {
        $sixunModel = new SixunOpera();
        $sixunModel->asyncCate();
        exit_json();
    }


}