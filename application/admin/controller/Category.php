<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/15
 * Time: 10:18
 */

namespace app\admin\controller;

class Category extends BaseController
{
    protected function _initialize()
    {
        parent::_initialize();
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
        $cateList = model('ProductCate')->where('parent_id', $categoryId)->select();
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
            $res = model('ProductCate')->allowField(true)->save($data);
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
            $res = model('ProductCate')->allowField(true)->save($data, ['id' => $data['id']]);
            if ($res) {
                exit_json();
            } else {
                exit_json(-1, '保存失败');
            }
        }
        $cateId = input('categoryId');
        $cate = model('ProductCate')->where('id', 'eq', $cateId)->find();
        $this->assign('cate', $cate);
        return $this->fetch();
    }

    /**
     * 删除分类
     */
    function delCate()
    {
        addAdminOperaLog();
        $ids = input('post.ids');
        $res = model('ProductCate')->whereIn('id', $ids)->whereOr('parent_id', 'in', $ids)->delete();
        if ($res) {
            exit_json();
        } else {
            exit_json(-1);
        }

    }
}