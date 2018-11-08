<?php

namespace app\admin\controller;

use app\admin\controller\Common;
use app\admin\model\Cate as CateModel;

class Cate extends Common
{
    //可以为某个或者某些操作指定前置执行的操作方法
    protected $beforeActionList = [
        // 'first',    //先执行该法方法
        // 'second' =>  ['except'=>'hello'],  //除了hello法方，
        'delsoncate'  =>  ['only'=>'del'],    //当执行del法方，才执行delsoncate法方
    ];

    public function lst()
    {

        //模型
        $cate = new CateModel();
        $cateres = $cate->cateTrees();
        $this->assign('cateres', $cateres);
        return view();
    }

    public function add()
    {
        $cate = new CateModel();
        if (request()->isPost()) {
        $data=input('post.');
        $addNum=$cate->save($data);
            if($addNum){
                $this->success('添加栏目成功！',url('lst'));
            }else{
                $this->error('添加栏目失败！');
            }
        }
        $cateres=$cate->catetrees();
        $this->assign('cateres',$cateres);
        return view();
    }

    public function edit($id)
    {
        $cate = new CateModel();
        if(request()->isPost()) {
            $data = input('post.');
            $save=$cate->save($data,['id'=>$data['id']]);
            if($save !== false){
                $this->success('修改栏目成功！',url('Cate/lst'));
            }else{
                $this->error('修改栏目失败！');
            }
            return;
        }
        $cateres=$cate->catetrees();
        $cateId=input('id');
        $cateNum=$cate->find($cateId);
        $this->assign(array(
            'cateres'=>$cateres,
            'cateNum'=>$cateNum,
        ));
        return view();
    }

    public function del()
    {
        $data=input('id');
        $del=db('cate')->delete($data);
        if($del){
            $this->success('删除栏目成功！',url('lst'));
        }else{
            $this->error('删除栏目失败！');
        }
    }

    public function delsoncate()
    {
        $cateid=input('id'); //要删除的当前栏目的id
        $cate=new CateModel();
        $sonids=$cate->getchildrenid($cateid);
//        dump($sonids);die;
        if($sonids){
            db('cate')->delete($sonids);
        }
    }
}