<?php
//小程序用
class ContentCan {
    /**
     * 内容审核
     */
    public function filter(){
        $type = "";
        $content = "";
        $openid = "";//必须有
        $access_token = $this->get_access_token();
        //审核文本
        if($type == 1){
            $url = "https://api.weixin.qq.com/wxa/msg_sec_check?access_token=".$access_token;
            $postdata = [
                "version"=>2,
                "openid"=>$openid,
                "scene"=>2,
                "content"=>$content
            ];
        }else{
            $url = "https://api.weixin.qq.com/wxa/media_check_async?access_token=".$access_token;
            //审核图片
            if($type == 2){
                $content_type = 2;
            //审核视频
            }elseif($type == 3){
                $content_type = 1;
            }
            $postdata = [
                "version"=>2,
                "openid"=>$openid,
                "scene"=>2,
                "media_url"=>$content,
                "media_type"=>$content_type
            ];
        }
        $res = $this->http_request($url,"post",$postdata);
        $res = json_decode($res,true);
        if($type == 1){
            if($res['result']['suggest'] == "pass"){
                
            }
        }elseif($type == 2){
            if($res['errmsg'] == "ok"){
                
            }
        }elseif($type == 3){
            if($res['errmsg'] == "ok"){
                
            }
        }
        
    }
    /**
     * 获取access_token
     */
    public function get_access_token(){
        $appid = "";
        $appsecret = "";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
        $res = $this->http_request($url,"get");
        $res = json_decode($res,true);
        $access_token = $res['access_token'];
        return $access_token;
    }
    /**
     * 发送请求
     */
    public function http_request($url,$method,$postdata = ""){
        //初始化
        $curl = curl_init() ;
        //设置抓取的url
        curl_setopt($curl,CURLOPT_URL,$url);
        //设置头文件的信息作为数据流输出
        curl_setopt( $curl,CURLOPT_HEADER,0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        //设置post方式提交
        if($method == "post"){
            curl_setopt($curl,CURLOPT_POST,1);
            $postdata = json_encode($postdata, JSON_UNESCAPED_UNICODE);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$postdata);
        }
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
}
