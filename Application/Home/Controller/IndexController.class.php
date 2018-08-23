<?php
namespace Home\Controller;
use Common\Model\NewHouseModel;
use Common\Model\OldHouseModel;
use Common\Model\RentalHouseModel;
use Common\Service\WeixinService;
use Think\Controller;
class IndexController extends Controller {

    public function _initialize(){
        $user = get_user();
        if($user){
            $this -> assign('isLogin',true);
        }else{
            $this -> assign('isLogin',false);
        }
        session('NavBar','Index');
    }

    public function index(){
        $wxService = WeixinService::getInstance();
        $openId = session('openId');
        if($wxService->isWeixinBrowser() && !$openId && !APP_DEBUG)
        {
            $authUrl = $wxService->buildAuth2Url(urlencode($this->getRedirectUrl()));
            redirect($authUrl);
            return;
        }
        else
        {
            $this->renderIndex();
        }
    }
    private function renderIndex()
    {
        //session('key',1);
        //查找出要轮播的图片
        $ads = M('ad') -> where(['status'=>2,'positions'=>'index']) -> order('sort asc') -> select();
        //查询楼盘
        //$houses = M('loupan') -> where(['is_hot'=>2, 'is_del' => 0,'status'=>1]) -> order('sort desc') ->limit(0,5) -> select();
        //$this -> assign('houses',$houses);
        $this -> assign('ads',$ads);
        $this -> display("index");
    }
    private function getRedirectUrl()
    {
        $url = 'http';
        if ($_SERVER["HTTPS"] == "on")
        {
            $url .= "s";
        }
        $url .= "://".$_SERVER["SERVER_NAME"];
        $url .= U('Index/weixinRedirect');
        return $url;
    }
    public function weixinRedirect()
    {
        $code = I('code');
        if($code)
        {
            $weixinService = WeixinService::getInstance();
            $wechatUser = $weixinService->getUserInfo($code);
            if($wechatUser['openId'])
            {
                session('openId', $wechatUser['openId']);
            }
            $this->renderIndex();
        }
        else
        {
            $this->redirect('Index/index');
        }

    }

    public function loadRecommendNewHouse(){
        $houses = D('NewHouse')->queryRecommendHouse();
        foreach ($houses as &$house)
        {
            //var_dump($house);
            $house['area'] = D('City')->findNameById($house['areaId']);
            $ranges = D('NewHouse')->queryHouseHuxingRange($house['id']);
            foreach ($ranges as $key=>$v)
            {
                $house[$key] = $v;
            }
            $house['others'] = json_decode($house['others'], true);
            $house['tags'] = json_decode($house['tags'], true);
            $house['typeCaption'] = D('Type')->findNameById($house['type']);
            $house['stateCaption'] = NewHouseModel::$StateCaption[$house['state']];
        }
        $tags = D('Tag')->queryNewHouseTags();
        $tagNames = array();
        foreach ($tags as $tag)
        {
            $tagNames[$tag['id']] = $tag['name'];
        }
        $this->assign('tagNames', $tagNames);
        $this->assign('houses', $houses);
        $this->display('recommend_newhouse_list');
    }

    public function loadRecommendOldHouse()
    {
        $houses = D('OldHouse')->queryRecommendHouse();
        foreach ($houses as &$house)
        {
            $house['area'] = D('City')->findNameById($house['areaId']);
            $house['others'] = json_decode($house['others'], true);
            $house['tags'] = json_decode($house['tags'], true);
            $house['typeCaption'] = D('Type')->findNameById($house['type']);
            $house['stateCaption'] = OldHouseModel::$StateCaption[$house['state']];
        }
        $tags = D('Tag')->queryOldHouseTags();
        $tagNames = array();
        foreach ($tags as $tag)
        {
            $tagNames[$tag['id']] = $tag['name'];
        }
        $this->assign('tagNames', $tagNames);
        $this->assign('houses', $houses);
        $this->display('recommend_oldhouse_list');
    }
    public function loadRecommendRentalHouse()
    {
        $houses = D('RentalHouse')->queryRecommendHouse();
        foreach ($houses as &$house)
        {
            $house['area'] = D('City')->findNameById($house['areaId']);
            $house['others'] = json_decode($house['others'], true);
            $house['tags'] = json_decode($house['tags'], true);
            $house['typeCaption'] = D('Type')->findNameById($house['type']);
            $house['stateCaption'] = RentalHouseModel::$StateCaption[$house['state']];
        }
        $tags = D('Tag')->queryRentalHouseTags();
        $tagNames = array();
        foreach ($tags as $tag)
        {
            $tagNames[$tag['id']] = $tag['name'];
        }
        $this->assign('Source', RentalHouseModel::$Source);
        $this->assign('tagNames', $tagNames);
        $this->assign('houses', $houses);
        $this->display('recommend_rentalhouse_list');
    }
    public function pingtai(){
        $this -> display();
    }
    public function news(){
        $id = I('id');
        $info = M('wen_zhang') -> where(['id'=>$id]) -> find();
        $this -> assign('info',$info);
        $this -> display();

    }
    public function trade()
    {
        $user = get_user();
        if(!$user)
        {
            $this->redirect('Login/code_login');
            return;
        }
        $houseType = I('houseType');
        $houseId = I('houseId');
        $this->assign('houseType', $houseType);
        $this->assign('houseId', $houseId);
        $this->display();
    }

    public function search() {
    }

}