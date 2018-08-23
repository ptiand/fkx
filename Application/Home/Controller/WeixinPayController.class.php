<?php
namespace Home\Controller;
use Think\Controller;

class WeixinPayController extends Controller
{
    public function index(){
        $this->display();
    }
   
    /**
     * 微信支付目标链接
     */
    public function pay(){
        
        $uri = explode('/',$_SERVER["REQUEST_URI"]);
        $b_id = $uri[count($uri)-1];
        if(empty($b_id) && !is_numeric($b_id))
        {
            $content = '异常错误，请刷新重试！';
            $this->assign('content',$content);
            $this->display();
        }
        
        $b_info = M('bills')->where(array('id'=>$b_id))->find();
        if(empty($b_info))
        {
            $content = '异常错误，请刷新重试！';
            $this->assign('content',$content);
            $this->display();
        }
        
        $where = array( 'id' => $b_info['a_id']);
        $agreement_info = M('agreement')->where($where)->find();
        if(empty($agreement_info))
        {
            $content = '异常错误，请刷新重试！';
            $this->assign('content',$content);
            $this->display();
        }
        
        $where = array('id' => $agreement_info['buy_user_id']);
        $user_info = M('users')->where($where)->find();
        if(empty($user_info) || empty($user_info['openid']))
        {
            $content = '目标用户未绑定微信，异常错误……';
            $this->assign('content',$content);
            $this->display();
        }
        
        
        //print_r($idd);exit;
        Vendor('Weixinpay.Weixinpay');
        $wxpay = new \Weixinpay();
        $out_trade_no = $_GET['out_trade_no'] ? $_GET['out_trade_no'] : 0;
        /*
         * $out_trade_no 为自己业务逻辑中要支付的订单号
         *      可从POST数据中提取，具体安全起见可自行加密操作 此处仅举例测试数据
         * $wxPayTag  微信支付开启状态
         *      一般为后台监控设置 此处测试定为开启状态
         * $pay_status 获取订单支付状态
         *      一般为支付前的订单查询 此处测试直接为未支付状态
         *
         */
        $wxPayTag = true;
        $pay_status = $b_info['pay_state'];
        if (!$wxPayTag){
            $content = 'Sorry,系统维护中，暂停支付';
        }else{
            if ($pay_status){
                $content = '该订单已支付，请勿重复提交';
            }else{
                // 获取jssdk需要用到的数据
                $data=$wxpay->getParameters($user_info['openid'], $b_id);
                
                $where = array('id' => $b_id);
                $update = array('pay_no' => $data['out_trade_no']);
                M('bills')->where($where)->save($update);
                
                // 将数据分配到前台页面
                $assign=array(
                    'data'=>json_encode($data)
                );
                $out_trade_no = $data['out_trade_no'];
                $content = '订单，待支付中...';
            }
        }
        $this->assign('out_trade_no',$out_trade_no);
        $this->assign('content',$content);
        $this->assign($assign);
        $this->display();
    }
    /**
     * 微信支付监听接口 判断是否完成了微信支付操作
     */
    public function notify_wx(){
        // ↓↓↓下面的file_put_contents是用来简单查看异步发过来的数据 测试完可以删除；↓↓↓
        // 获取xml
        /*$xml=file_get_contents('php://input', 'r');
        //转成php数组 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data= json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA));
        file_put_contents('.notify.log', $data);*/
        // ↑↑↑上面的file_put_contents是用来简单查看异步发过来的数据 测试完可以删除；↑↑↑
        // 导入微信支付sdk
        Vendor('Weixinpay.Weixinpay');
        $wxpay=new \Weixinpay();
        $result = $wxpay->notify();
        if ($result) {
            $b_id = $result['out_trade_no'];
            if(empty($b_id) && !is_numeric($b_id))
            {
                $content = '异常错误，请刷新重试！';
                $this->assign('content',$content);
                $this->display();
            }

            $b_info = M('bills')->where(array('pay_no'=>$b_id))->find();
            if(empty($b_info))
            {
                $content = '异常错误，请刷新重试！';
                $this->assign('content',$content);
                $this->display();
            }
            //更新支付状态
            $update = array('pay_state' => 1, 'pay_date' => date('Y-m-d', time()));
            M('bills')->where(array('id'=>$b_info['id']))->save($update);
            
            //给用户增加余额
            $where = array('id' => $b_info['a_id']);
            $agreement_info = M('agreement')->where($where)->find();
            
            $where = array('id' => $agreement_info['sell_user_id']);
            $user_info = M('users')->where($where)->find();
            
            $update = array('balance' => $user_info['balance'] + $b_info['total_price']);
            M('users')->where($where)->save($update);
            
            return true;
            $out_trade_no = explode('M',$result['out_trade_no'])[0] ;
            $this->toUpdatePayInfo($out_trade_no,'wx');
            //TODO 进行页面跳转
        }
    }
    /**
     * 进行更新支付后的数据处理
     * @param $value 订单号
     * @param string $pay_type 支付方式 ：TODO 可用于支付方式的后续数据处理
     * @return mixed
     */
    public function toUpdatePayInfo($value,$pay_type = 'wx')
    {
        $order_info = M('order_info')
            ->field('pay_status,order_amount,user_id')
            ->where('order_sn = ' . $value)
            ->find();
        // 更新的条件
        if ($order_info['pay_status'] == 0) {
            //TODO 执行数据库更新操作
            //.......
        }
        return true;
    }
    /*-------------------一道奇怪的分界线--没理由--就是为了分界！---------------------------------------*/
    /**
     * 微信配置 处理订单支付金额
     * @param $out_trade_no 支付的订单号
     * TODO $all_order_amount 此为测试数值 可根据实际情况进行赋值
     * @return float|int|mixed
     */
    public function wxPayOrder($b_id){
        //$out_trade_no 可用于真实业务的数据处理
        $b_info = M('bills')->where(array('id'=>$b_id))->find();
        return $b_info['total_price'];
    }
}