<?php

// 引入文字识别OCR SDK
require_once '../AipOcr.php';

// 定义常量
const APP_ID = '10695495';
const API_KEY = 'uisDM9aAWhCNRn1OXlpSkGGK';
const SECRET_KEY = 'W6LbRRnI5c7zEGlni3eeFZegaFP3zQ22';
// 初始化
$aipOcr = new AipOcr(APP_ID, API_KEY, SECRET_KEY);

// 身份证识别
//echo json_encode($aipOcr->idcard(file_get_contents('idcard.jpg'), true,JSON_PRETTY_PRINT),JSON_UNESCAPED_UNICODE);
//var_dump($aipOcr->idcard(file_get_contents('idcard.jpg'),true,JSON_PRETTY_PRINT)) ;
// 银行卡识别 
// echo json_encode($aipOcr->bankcard(file_get_contents($path)));

// 通用文字识别(含文字位置信息)
echo json_encode($aipOcr->general(file_get_contents('http://fangweb-test.stor.sinaapp.com/test.jpg')),JSON_UNESCAPED_UNICODE);


// 通用文字识别(不含文字位置信息)
// echo json_encode($aipOcr->basicGeneral(file_get_contents('general.png')));

// 网图OCR识别
// echo json_encode($aipOcr->webImage(file_get_contents('general.png')));

// 生僻字OCR识别
// echo json_encode($aipOcr->enhancedGeneral(file_get_contents('general.png')));
//接收微信小程序上传的图片
function RecevieWxFile(){
   // $s=new SaeStorage();//新浪云专有的代码
$tmpname=$_FILES['flieup']['name'];
$tail=strstr($tmpname,'.');
$filename="image".$tail;
$path='./upfile/'.iconv("UTF-8","gbk",$filename);
$saepath=$path;
if(!move_uploaded_file($_FILES['flieup']['tmp_name'],$path)){
    echo "上传失败";
}
}
function abc(){
    $s=new SaeStorage();
    ob_start();//二进制文件
    readfile($_FILES['flieup']['tmp_name']);
    $img=ob_get_contents();
    ob_end_clean();
    $size=strlen($img);
    file_put_contents(SAE_TMP_PATH.'/bg.jpg',$img);
    if ($s->upload("test","test.jpg",SAE_TMP_PATH.'/bg.jpg')){
        echo "上传成功";
    }
    else echo "上传失败";
}
//RecevieWxFile();