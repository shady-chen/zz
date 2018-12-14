<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/12
 * Time: 21:23
 */

namespace app\admin\controller;




use app\index\model\AppOrder;
use app\admin\model\SystemBanks;
use app\index\model\SystemSetting;


class Admin extends Base
{
    function _initialize()
    {
        if(!$this->isAdmin()){
            return $this->error('您还没有登录！','/admin/user/login');
        }
        //parent::_initialize(); // TODO: Change the autogenerated stub
    }

    public function index(){
        return $this->fetch();
    }

    /**
     * 通知列表
     * @return mixed
     */
    public function article_list(){
        return $this->fetch();
    }


    /**
     * 系统发送通知
     * @return mixed
     */
    public function article_add(){
        return $this->fetch();
    }

    /**
     * 订单列表
     * @return mixed
     */
    public function order_list(){
        $appOrder = new AppOrder();
        $data = $appOrder->order('create_time desc')->paginate(10);
        $this->assign('data',$data);
        $this->assign('page',$data->render());
        return $this->fetch();
    }

    /**
     * 银行卡列表
     * @return mixed
     */
    public function banks_list(){
        $SystemBanks = new SystemBanks();
        $data = $SystemBanks->order('create_time desc')->paginate(10);
        $this->assign('data',$data);
        $this->assign('page',$data->render());
        return $this->fetch();
    }

    /**
     * 系统添加银行卡
     * @return mixed
     */
    public function banks_add(){
        return $this->fetch();
    }

    /**
     * 系统添加银行卡
     * @return json
     */
    public function doAddBanks(){
        $params = $this->request->param();

        $SystemBanks = new SystemBanks();
        $data = [
            'bank_num'=>$params['bank_num'],
            'bank_which'=>$params['bank_which'],
            'bank_where'=>$params['bank_where'],
            'name'=>$params['name'],
            'is_use'=>1,
            'create_time'=>time(),
        ];
        $SystemBanks->save($data);

        return json(['msg'=>'添加成功','status'=>200]);

    }
    /**
     * 跳转系统设置页面
     */
    public function system_base(){
        $sys_setting = new SystemSetting();
        $data = $sys_setting->find();
        $this->assign('data',$data);
        return $this->fetch();

    }
    /**
     * 系统设置的修改
     */
    public function system_base_update(){
        $params = $this->request->param();
        $sys_setting = new SystemSetting();
        if($params['bonus_rule']<=0){
            return json(['msg'=>'阶级金额不能小于0','status'=>0]);
        }
        if($params['star_time']<0 || $params['star_time']>=$params['end_time'] || $params['end_time']>23){
            return json(['msg'=>'开始时间不能大于结束时间','status'=>0]);
        }
        if($params['per_total']<=0){
            return json(['msg'=>'发包总金额不能小于0','status'=>0]);
        }
        if($params['how_many']<=0){
            return json(['msg'=>'发包不能为小于1','status'=>0]);
        }
        if($params['how_long']<=0){
            return json(['msg'=>'发包间隔时间不能小于0','status'=>0]);
        }
        $data = [
            'bonus_rule'=>$params['bonus_rule'],
            'per_money'=>$params['per_money'],
            'star_time'=>$params['star_time'],
            'per_total'=>$params['per_total'],
            'how_many'=>$params['how_many'],
            'end_time'=>$params['end_time'],
            'how_long'=>$params['how_long'],
            'bunus_money'=>$params['bunus_money'],
        ];

        $sys_setting->where(['id'=>1])->update($data);
        return json(['msg'=>'修改成功','status'=>200]);
    }


}