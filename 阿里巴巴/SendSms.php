<?php
namespace app\api\controller;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class SendSms{
    /**
     * 发送短信
     */
    public function send(){
        $phone = "";
        $accessKeyId = "";
        $accessKeySecret = "";
        $code = $this->GetRandId(6);
        if(file_exists("./phone/".$phone.".txt")){
            $lst = filemtime("./phone/".$phone.".txt");
            if((time()-$lst)<60){
                
            }
        }
        if(file_exists("./phone/".$phone.".txt")){
            unlink("./phone/".$phone.".txt");
        }
        file_put_contents("./phone/".$phone.".txt",$code);
        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
        ->regionId('ap-northeast-1')
        ->asDefaultClient();
        try {
        $result = AlibabaCloud::rpc()
                    ->product('Dysmsapi')
                    // ->scheme('https') // https | http
                    ->version('2017-05-25')
                    ->action('SendSms')
                    ->method('POST')
                    ->host('dysmsapi.aliyuncs.com')
                    ->options([
                                    'query' => [
                                    'PhoneNumbers' => $phone,
                                    'SignName' => "",
                                    'TemplateCode' => "",
                                    'TemplateParam' => "{\"code\":\"$code\"}",
                                    ],
                                ])
                    ->request();
        //print_r($result->toArray());
        } catch (ClientException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
        }
        $result = $result->toArray();
        if($result["Code"] == "OK"){
            
        }else{
            
        }
    }
    /**
     * 生成随机验证码
     */
    public function GetRandId($length){
        //字符组合
        $str = '0123456789';
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
         $num=mt_rand(0,$len);
         $randstr .= $str[$num];
        }
        return $randstr;
    }

}