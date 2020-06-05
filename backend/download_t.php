<?php
		$file_path=$_POST['url'];
		$id=$_POST['id'];
//		$path="./Submitlist/".$file_path."/";
		$path="/www/wwwroot/ashinz.cn/nuaa/Submitlist/".$file_path."/";
/**
 * 总接口
 * @param $dir_path 需要压缩的目录地址（绝对路径）
 * @param $zipName 需要生成的zip文件名（绝对路径）
 */
function zip($dir_path,$zipName){
    $relationArr = [$dir_path=>[
        'originName'=>$dir_path,
        'is_dir' => true,
        'children'=>[]
    ]];
    modifiyFileName($dir_path,$relationArr[$dir_path]['children']);
    $zip = new ZipArchive();
    $zip->open($zipName,ZipArchive::CREATE);
    zipDir(array_keys($relationArr)[0],'',$zip,array_values($relationArr)[0]['children']);
    $zip->close();
    restoreFileName(array_keys($relationArr)[0],array_values($relationArr)[0]['children']);
}

/**
 * 递归添加文件进入zip
 * @param $real_path 在需要压缩的本地的目录
 * @param $zip_path zip里面的相对目录
 * @param $zip ZipArchive对象
 * @param $relationArr 目录的命名关系
 */
function zipDir($real_path,$zip_path,&$zip,$relationArr){
    $sub_zip_path = empty($zip_path)?'':$zip_path.'\\';
    if (is_dir($real_path)){
        foreach($relationArr as $k=>$v){
            if($v['is_dir']){  //是文件夹
                $zip->addEmptyDir($sub_zip_path.$v['originName']);
                zipDir($real_path.'\\'.$k,$sub_zip_path.$v['originName'],$zip,$v['children']);
            }else{ //不是文件夹
                $zip->addFile($real_path.'\\'.$k,$sub_zip_path.$k);
                $zip->deleteName($sub_zip_path.$v['originName']);
                $zip->renameName($sub_zip_path.$k,$sub_zip_path.$v['originName']);
            }
        }
    }
}

/**
 * 递归将目录的文件名更改为随机不重复编号，然后保存原名和编号关系
 * @param $path 本地目录地址
 * @param $relationArr 关系数组
 * @return bool
 */
function modifiyFileName($path,&$relationArr){
    if(!is_dir($path) || !is_array($relationArr)){
        return false;
    }
    if($dh = opendir($path)){
        $count = 0;
        while (($file = readdir($dh)) !== false){
            if(in_array($file,['.','..',null])) continue; //无效文件，重来
            if(is_dir($path.'\\'.$file)){
                $newName = md5(rand(0,99999).rand(0,99999).rand(0,99999).microtime().'dir'.$count);
                $relationArr[$newName] = [
                    'originName' => iconv('GBK','UTF-8',$file),
                    'is_dir' => true,
                    'children' => []
                ];
                rename($path.'\\'.$file, $path.'\\'.$newName);
                modifiyFileName($path.'\\'.$newName,$relationArr[$newName]['children']);
                $count++;
            }
            else{
                $extension = strchr($file,'.');
                $newName = md5(rand(0,99999).rand(0,99999).rand(0,99999).microtime().'file'.$count);
                $relationArr[$newName.$extension] = [
                    'originName' => iconv('GBK','UTF-8',$file),
                    'is_dir' => false,
                    'children' => []
                ];
                rename($path.'\\'.$file, $path.'\\'.$newName.$extension);
                $count++;
            }
        }
    }
}

/**
 * 根据关系数组，将本地目录的文件名称还原成原文件名
 * @param $path 本地目录地址
 * @param $relationArr 关系数组
 */
function restoreFileName($path,$relationArr){
    foreach($relationArr as $k=>$v){
        if(!empty($v['children'])){
            restoreFileName($path.'\\'.$k,$v['children']);
            rename($path.'\\'.$k,iconv('UTF-8','GBK',$path.'\\'.$v['originName']));
        }else{
            rename($path.'\\'.$k,iconv('UTF-8','GBK',$path.'\\'.$v['originName']));
        }
    }
}
/*
function addFileToZip($path,$zip){
  $handler=opendir($path); //打开当前文件夹由$path指定。
  while(($filename=readdir($handler))!==false){
    if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..'，不要对他们进行操作
      if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
        addFileToZip($path."/".$filename, $zip);
      }else{ //将文件加入zip对象
        $zip->addFile($path."/".$filename);
      }
    }
  }
  @closedir($path);
}
 
 
$zip_name=$file_path.".zip";
$zip=new ZipArchive();
if($zip->open($zip_name, ZipArchive::OVERWRITE)=== TRUE){
  addFileToZip($path, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
  $zip->close(); //关闭处理的zip文件
}*/
$zip_name=$file_path.".zip";
$user_path="F:/phpstudy/PHPTutorial/WWW/Submitlist/zip/".$zip_name;
zip($path,$user_path);
//copy($zip_name,iconv("UTF-8", "GBK",$user_path));
//检查文件是否存在  
$down_path="Submitlist/zip/".$zip_name;
if (file_exists ( $user_path)) {    
    $ret=array('code'=>'200','url'=>$down_path);
	echo json_encode($ret);
}
else 
{
	$ret=array('code'=>'201','url'=>'null');
	echo json_encode($ret);
}

?>