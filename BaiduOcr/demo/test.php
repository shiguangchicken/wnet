<?php
/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2017/12/27
 * Time: 15:33
 */
    if (!empty($_FILES['im']['name'])){
        if ($_FILES['im']['error']>0){
            echo "上传错误";
            switch ($_FILES['file1']['error']){
                case 1:
                    echo "上传文件大小超出配置文件规定";
                    break;
                case 2:
                    echo "上传文件大小超过表单中约定值";
                    break;
                case 3:
                    echo "上传文件不全";
                    break;
                case 4:
                    echo "没有上传文件";
                    break;
            }
        }
        else{
            if (!is_dir("./upfile")){
                mkdir("./upfile/");
            }
            $path='./upfile/'.iconv("UTF-8","gbk",$_FILES['im']['name']);
            if (is_uploaded_file($_FILES['im']['tmp_name'])){
                if (!move_uploaded_file($_FILES['im']['tmp_name'],$path)){
                    echo "上传失败";
                }
                else{
                    echo "文件".$_FILES['im']['name']."上传成功";
                }
            }
        }
    }
    else echo "空文件";