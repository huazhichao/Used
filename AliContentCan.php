<?php

include_once 'aliyuncs/aliyun-php-sdk-core/Config.php';

use \Green\Request\V20180509 as Green;

class AliContentCan {
    /**
     * 内容审核--接口用
     */
    public function filter(){
        $type = 1;
        $content = "";
        // 请替换成您的AccessKey ID、AccessKey Secret。
        $iClientProfile = \DefaultProfile::getProfile("cn-shanghai", "LTAI5tCsWdb9hfyuA7auBG86", "gWdvIvCTiSommSJROqrce5tfXu0lCa");
        \DefaultProfile::addEndpoint("cn-shanghai", "cn-shanghai", "Green", "green.cn-shanghai.aliyuncs.com");
        $client = new \DefaultAcsClient($iClientProfile);
        //文本
        if($type == 1){
            $request = new Green\TextScanRequest();
            $task1 = array(
                'content' => $content
            );
            $scenes = array("antispam");
        //图片
        }elseif($type == 2){
            $request = new Green\ImageSyncScanRequest();
            $task1 = array(
                'url' => $content,
            );
            $scenes = array("porn","terrorism","ad","live","qrcode","logo");
        //视频（异步的）
        }elseif($type == 3){
            $request = new Green\VideoAsyncScanRequest();
            $task1 = array(
                'url' => $content,
            );
            $scenes = array("porn", "terrorism");
        
        }
        $setcontent = json_encode(array("tasks" => array($task1),
        "scenes" => $scenes));
        
        $request->setMethod("POST");
        $request->setAcceptFormat("JSON");
        $request->setContent($setcontent);
        try {
            $response = $client->getAcsResponse($request);
            // var_dump($response);
            // exit;
            if(200 == $response->code){
                $taskResults = $response->data;
                if($type == 3){
                    $taskId = $taskResults[0]->taskId;
                }
                foreach($taskResults as $taskResult){
                    if(200 == $taskResult->code){
                        $sceneResults = $taskResult->results;
                        foreach ($sceneResults as $sceneResult){
                            $scene = $sceneResult->scene;
                            $suggestion = $sceneResult->suggestion;
                            // 根据scene和suggetion做相应的处理。
                            if($suggestion != "pass"){

                            }
                        }
                    }else{
                        print_r("task process fail:" + $response->code);
                    }
                }
            }else{
                print_r("detect not success. code:" + $response->code);
            }
        }catch(\Exception $e){
            print_r($e);
        }
    }
}
