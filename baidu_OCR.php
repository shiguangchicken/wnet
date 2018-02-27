<?php
/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2018/2/14
 * Time: 14:23
 */
// 从服务器获取信息
$access_token=null;
date_default_timezone_set("PRC");
//access_token":"24.93f68a297e04da0461effe2df93d447f.2592000.1521184045.282335-10695495"
function request_post($url = '', $param = '') {
    if (empty($url) || empty($param)) {
        return false;
    }

    $postUrl = $url;
    $curlPost = $param;
    $curl = curl_init();//初始化curl
    curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($curl);//运行curl
    curl_close($curl);

    return $data;
}
//获取百度的access_token 保存到a_token.txt中
//appid=10695495
function get_atoken(){
    $url = 'https://aip.baidubce.com/oauth/2.0/token';
    $post_data['grant_type']       = 'client_credentials';
    $post_data['client_id']      = 'uisDM9aAWhCNRn1OXlpSkGGK';//api key
    $post_data['client_secret'] = 'W6LbRRnI5c7zEGlni3eeFZegaFP3zQ22';//secret key
    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $res = request_post($url, $post_data);
    $tempvar=json_decode($res)->{'access_token'};
    save_var($tempvar);
    return $tempvar;
    //保存到txt中
    //var_dump($res);

}
function save_var($tempvar){
    $time_s=time();
    $time=date('Y-m-d h-i-s',$time_s);
    $save_info=$tempvar."--------".$time_s."--------".$time;
    $file=fopen("a_token.txt",'w+');
    fwrite($file,$save_info);
    fclose($file);
}
    $file=fopen('a_token.txt','r');
    $content=fread($file,1024);
    $read_info=explode("--------",$content);
    $time_now=time();
    if ($read_info[0]==''|| ($time_now-$read_info[1])/3600/24>29)
        $access_token=get_atoken();
    else $access_token=$read_info[0];

    echo '{"access_token":'.$access_token."}";
//将图片与百度OCR连接,返回识别的文字内容
function get_OCR(){
    //通用文字识别url
    global $access_token;
    $url_normal="https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic"."?access_token=".$access_token;
    base64_imge("ocrtest.png");
    curl_post($url_normal,file_get_contents("64code.png"));

}
//对图像进行base64编码与解码
function base64_imge($filename){
    $imageInfo=getimagesize($filename);
    $imageData=fread($fp1=fopen($filename,'r'),$imageInfo[0]*$imageInfo[1]);
    $base64_encod_imge=base64_encode($imageData);
    // echo $imageData;
    $data= base64_decode($base64_encod_imge);//对截取后的字符使用base64_decode进行解码
    file_put_contents("64code.png", $data); //写入文件并保存
    fclose($fp1);
}
//http请求
function curl_get($url){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    } else {
        return $response;
    }
}
//post
function curl_post($url,$curl_data){
    $curl = curl_init();
    $options = array(
        CURLOPT_RETURNTRANSFER => true,         // return web page
        CURLOPT_HEADER         => false,        // don't return headers
        CURLOPT_FOLLOWLOCATION => true,         // follow redirects
        CURLOPT_ENCODING       => "",           // handle all encodings
        CURLOPT_USERAGENT      => "spider",     // who am i
        CURLOPT_HTTPHEADER=>"application/x-www-form-urlencoded",
        CURLOPT_AUTOREFERER    => true,         // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
        CURLOPT_TIMEOUT        => 120,          // timeout on response
        CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
        CURLOPT_POST            => 1,            // i am sending post data
        CURLOPT_POSTFIELDS     => $curl_data,    // this are my post vars
        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
        CURLOPT_SSL_VERIFYPEER => false,        //
        CURLOPT_VERBOSE        => 1                //
    );
    //
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        return false;
    } else {
        return $response;
    }
}
?>