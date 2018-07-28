<?php
/**
 * 商品控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/19
 * Time: 14:06
 */

namespace app\admin\controller;

class Product extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $cateList = model('ProductCate')->field('id, name, parent_id pId')->select();
        $this->assign('cateList', $cateList);
        return $this->fetch();
    }

    /**
     * 根据分类获取商品
     */
    public function plist()
    {
        $cateId = input('cateId');
        if ($cateId) {
            $goodsList = model('Product')->where('cate_id', $cateId)->select();
        } else {
            $goodsList = [];
        }
        $this->assign('goodsList', $goodsList);
        return $this->fetch();
    }

    /**
     * 手动添加商品
     */
    public function productAdd()
    {
        if (request()->isAjax()) {
            $data = input('post.');
            if (isset($data['img'])) {
                $data['img'] = join(',', $data['img']);
            } else {
                $data['img'] = '';
            }
            $res = model('Product')->allowField(true)->save($data);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '添加失败');
            }
        }
        $list = model('ProductCate')->field('id, name, parent_id')->select();
        $cateTree = getTree($list, 0);
        $this->assign('cateTree', $cateTree);
        return $this->fetch();
    }

    /**
     * 编辑商品
     */
    public function productEdit()
    {
        $productModel = new Goodslibrary();
        if (request()->isAjax()) {
            $data = input('post.');
            if (isset($data['img'])) {
                $data['img'] = join(',', $data['img']);
            } else {
                $data['img'] = '';
            }
            $res = $productModel->allowField(true)->save($data, ['id' => $data['id']]);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '添加失败');
            }
        }
        $product = $productModel->where('id', input('goodid'))->find();
        $cateModel = new Goodslibrarycate();
        $list = $cateModel->field('id, name, pid parent_id')->select();
        $cateTree = getTree($list, 0);
        $pid = $cateModel->where('id', $product['cateId'])->value('pid');
        $this->assign('pid', $pid);
        $this->assign('product', $product);
        $this->assign('cateTree', $cateTree);
        return $this->fetch();
    }

    /**
     * 保存产品图片
     */
    function addProductImg()
    {
        $file = request()->file('file');
        if ($file) {
            $info = $file->move(__UPLOAD__ . '/goodsimg/user', md5(microtime() . rand(1000, 9999)));
            if ($info) {
                $saveName = $info->getSaveName();
                $path = "/upload/goodsimg/user/" . $saveName;
//                $img = "<input type='hidden' name='img[]' value='" . $path . "'/>";
                exit_json(1, '操作成功', $path);
            } else {
                // 上传失败获取错误信息
                exit_json(-1, $file->getError());
            }
        }
        exit_json();
    }

    /**
     * 根据id删除数据
     */
    function delData()
    {
        $ids = input('idstr');
        $goodModel = new Goodslibrary();
        $res = $goodModel->where('id', 'in', $ids)->delete();
        if ($res) {
            exit_json();
        } else {
            exit_json('操作失败');
        }
    }
}