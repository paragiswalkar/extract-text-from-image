<?php
$url = 'https://api.havenondemand.com/1/api/sync/ocrdocument/v1';
 
$output_dir = "../image-read/uploads/";

if(isset($_FILES["file"]))
{
	$maxsize      = 99999999;
	$size = getimagesize($_FILES['file']['tmp_name']);
	$image_type   = $size['mime'];
	$data_arr = array();
	$fileName = md5(date('Y-m-d H:i:s:u')).$_FILES["file"]["name"]; //unique filename
	
	if($_FILES['file']['size'] < $maxsize )
    {
		/***  get the image source ***/
        if($image_type=='image/jpeg'){
            //move the file to uploads folder
			if (move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName)) {
			  echo "<P>FILE UPLOADED TO: $output_dir.$fileName</P>";
			} else {
			  echo "<P>MOVE UPLOADED FILE FAILED!</P>";
			  print_r(error_get_last());
			}
        }elseif($image_type=='image/png'){
            //move the file to uploads folder
			if (move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName)) {
			  echo "<P>FILE UPLOADED TO: $output_dir.$fileName</P>";
			} else {
			  echo "<P>MOVE UPLOADED FILE FAILED!</P>";
			  print_r(error_get_last());
			}
        }
		 
		 
		//multipart form post using CURL
		$filePath = realpath($output_dir.$fileName);
		
		$post = array('apikey' => '83c9208b-c536-481a-aa91-5a79aab324a0',
						'mode' => 'document_photo',
						'file' =>'@'.$filePath);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec ($ch);
		curl_close ($ch);
		echo $result;
	 
		
		//If you want to return only text use this.
		/* $json = json_decode($result,true);
		if($json && isset($json['text_block']))
		{
			$textblock =$json['text_block'][0];
			echo $textblock['text'];
		} */
		 
		//remove the file
		unlink($filePath);
		
	}else{
        /*** throw an exception is image is not of type ***/
        throw new Exception("File Size Error");
    }
 }
?>