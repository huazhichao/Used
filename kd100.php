<?php

class Kd100 {
    /**
     * 根据订单号查询实时快递信息
     */
    public function synquery(){
        //参数设置
        $key = "";                        //客户授权key
        $customer = "";                   //查询公司编号
        $param = array (
            'com' => "",             //快递公司编码
            'num' => "",//快递单号
            'phone' => '',                //手机号
            'from' => '',                 //出发地城市
            'to' => '',                   //目的地城市
            'resultv2' => '1'             //开启行政区域解析
        );

        //请求参数
        $post_data = array();
        $post_data["customer"] = $customer;
        $post_data["param"] = json_encode($param);
        $sign = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($sign);

        $url = 'http://poll.kuaidi100.com/poll/query.do';    //实时查询请求地址

        $params = "";
        foreach ($post_data as $k=>$v) {
            $params .= "$k=".urlencode($v)."&";              //默认UTF-8编码格式
        }
        $post_data = substr($params, 0, -1);

        //发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $data = json_decode($result,true);
        if(isset($data['result']) && $data['result'] == false){
            $error['status'] = "无此订单信息";
            
        }
        if($data['com'] == "zhongtong"){
            $data['company'] = "中通快递";
        }elseif ($data['com'] == "shunfeng"){
            $data['company'] = "顺丰快递";
        }
        //订单状态
        $oss = ["1"=>"揽收","0"=>"在途","5"=>"派件","3"=>"签收","6"=>"退回","4"=>"退签","7"=>"转投"
        ,"2"=>"疑难","8"=>"清关","14"=>"拒签"];
        $data['status'] = $oss[$data['state']];
        foreach ($data['data'] as $key=>$val){
            $time = explode(" ",$val['time']);
            $data['data'][$key]['ztime'] = substr($time[1],0,5);
            $data['data'][$key]['zdate'] = $time[0];
        }
        
    }
}


