<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="./static/dist/js/vendor/jquery.min.js"></script>

<!--<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>  -->

<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">


<!--

-->
<!-- <script type="text/javascript" src="./static/js/jquery-ui-datepicker.js"></script> -->
<link href="./static/css/datepicker.css" rel="stylesheet">
<script src="./static/js/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript" src="./static/dist/js/flat-ui.min.js"></script>
<?php
/******************************************************************************

参数说明:
$max_file_size  : 上传文件大小限制, 单位BYTE
$destination_folder : 上传文件路径
$watermark   : 是否附加水印(1为加水印,其他为不加水印);

使用说明:
1. 将PHP.INI文件里面的"extension=php_gd2.dll"一行前面的;号去掉,因为我们要用到GD库;
2. 将extension_dir =改为你的php_gd2.dll所在目录;
******************************************************************************/

//上传文件类型列表
$uptypes=array(
    'image/jpg',
    'image/jpeg',
    'image/png',
    'image/pjpeg',
    'image/gif',
    'image/bmp',
    'image/x-png'
);

$max_file_size=2000000;     //上传文件大小限制, 单位BYTE
$destination_folder="photo/"; //上传文件路径
$watermark=0;      //是否附加水印(1为加水印,其他为不加水印);
$watertype=1;      //水印类型(1为文字,2为图片)
$waterposition=1;     //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring="http://www.xplore.cn/";  //水印字符串
$waterimg="xplore.gif";    //水印图片
$imgpreview=0;      //是否生成预览图(1为生成,其他为不生成);
$imgpreviewsize=1/2;    //缩略图比例
$overwrite = true;
?>
<html>
<head>
<title>Photo Upload</title>
<style type="text/css">
<!--
body
{
     font-size: 9pt;
}
input
{
     background-color: #66CCFF;
     border: 1px inset #CCCCCC;
}
-->
</style>
</head>

<body>
<form enctype="multipart/form-data" method="post" name="upform">
  Photo Upload:
  <input name="upfile" class="form-control" type="file">
  <input type="submit" style="margin:10px 0" class="btn btn-primary" value="Upload"><br>
  Photo type that you can upload:<?=implode(', ',$uptypes)?>
</form>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!is_uploaded_file($_FILES["upfile"]["tmp_name"]))
    //是否存在文件
    {
         echo "Photo not exists!";
         exit;
    }

    $file = $_FILES["upfile"];
    if($max_file_size < $file["size"])
    //检查文件大小
    {
        echo "Photo is too big!";
        exit;
    }

    if(!in_array($file["type"], $uptypes))
    //检查文件类型
    {
        echo "Not supporting file type!".$file["type"];
        exit;
    }

    if(!file_exists($destination_folder))
    {
        mkdir($destination_folder);
    }

    $filename=$file["tmp_name"];
    $image_size = getimagesize($filename);
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    if(isset($_GET['id'])){
    	$id= $_GET['id'];
    }
    else{
    	echo <<<script
    	<script type="text/javascript">
    		window.location.href = "./login.php";
    	</script>
script;
    	exit();
    }

    
    $destination = $destination_folder.$id.".".$ftype;
    if (file_exists($destination) && $overwrite != true)
    {
        echo "File already exists";
        exit;
    }

    if(!move_uploaded_file ($filename, $destination))
    {
        echo "Remove file error";
        exit;
    }

    $pinfo=pathinfo($destination);

    $fname=$pinfo["basename"];
    echo " <font color=red>Upload success</font><br>File name:  <font color=blue>".$destination_folder.$fname."</font><br>";
    echo " Width:".$image_size[0];
    echo " Length:".$image_size[1];
    echo "<br> Size:".$file["size"]." bytes";
	

    if($watermark==1)
    {
        $iinfo=getimagesize($destination,$iinfo);
        $nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
        $white=imagecolorallocate($nimage,255,255,255);
        $black=imagecolorallocate($nimage,0,0,0);
        $red=imagecolorallocate($nimage,255,0,0);
        imagefill($nimage,0,0,$white);
        switch ($iinfo[2])
        {
            case 1:
            $simage =imagecreatefromgif($destination);
            break;
            case 2:
            $simage =imagecreatefromjpeg($destination);
            break;
            case 3:
            $simage =imagecreatefrompng($destination);
            break;
            case 6:
            $simage =imagecreatefromwbmp($destination);
            break;
            default:
            die("Not supporting file type");
            exit;
        }

        imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
        imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);

        switch($watertype)
        {
            case 1:   //加水印字符串
            imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
            break;
            case 2:   //加水印图片
            $simage1 =imagecreatefromgif("xplore.gif");
            imagecopy($nimage,$simage1,0,0,0,0,85,15);
            imagedestroy($simage1);
            break;
        }

        switch ($iinfo[2])
        {
            case 1:
            //imagegif($nimage, $destination);
            imagejpeg($nimage, $destination);
            break;
            case 2:
            imagejpeg($nimage, $destination);
            break;
            case 3:
            imagepng($nimage, $destination);
            break;
            case 6:
            imagewbmp($nimage, $destination);
            //imagejpeg($nimage, $destination);
            break;
        }

        //覆盖原上传文件
        imagedestroy($nimage);
        imagedestroy($simage);
    }

    if($imgpreview==1)
    {
    echo "<br>图片预览:<br>";
    echo "<img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
    echo " alt=\"File preview:\rFile name:".$destination."\rUpload time:\">";
    }
    $path = "./".$destination_folder.$fname;
    require_once 'dbop.php';
    require_once 'parameter.config.php';
    $mydb = new mydb();
    $sql = "update staff_inf set photo='{$path}' where id = {$id}";
    echo $sql;
    $ret = $mydb->update($sql);
    if($ret){
    	echo "Photo upload success!<br>";
    	echo <<<submit
    	<script type="text/javascript">
	function submit(){

		window.opener.photoPath = "{$path}";
        window.opener.isLoad = 1;
		window.opener=null;
		window.open('','_self');
		window.close();
        		}

</script>
    	<button class="btn btn-primary"  onclick ="submit()" style="margin:10px 0">Submit</button>
submit;
    }
    else{
    	echo "Photo upload failed!<br>";
    }

    
}
?>


</body>
</html>