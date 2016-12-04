<?php
require_once('system_prerequisite.php');
require_once('func_common.php');		
validateUserSession();




//to upload file (move from temp dir to permanent storage folder)
function upload_file_epili($uploaddir,$uploadfilename,$allowedExtension='',$allowedSize='')
{
	//to list uploaded file
	if(is_string($uploadfilename['name']))
	{
		//uploaded file extension
		$uploadedFileExt = getExtension($uploadfilename['name']);
		
		$allowUpload = false;
		
		//filter extension
		if(!$allowedExtension || array_search($uploadedFileExt, $allowedExtension) !== false)
		{
			//default upload flag
			$allowUpload = true;
			
			//filter filesize
			if($allowedSize)
			{
				//uploaded file size in kb
				$uploadedFileSize = $uploadfilename['size'] / 1000;
			
				//file size bigger than max size
				if($uploadedFileSize > $allowedSize)
				{
					$allowUpload = false;
					$uploadError = $uploadfilename['name'].' tidak dimuatnaik kerana fail yang digunakan melebihi saiz yang dibenarkan!';
				}//eof if
			}//eof if
		}//eof if
		else
			$uploadError = $uploadfilename['name'].' tidak dimuatnaik kerana penggunaan jenis fail yang tidak dibenarkan!';
		
		//if allow to upload
		if($allowUpload)
		{
			$uploadfile = $uploaddir.STC.'_'.date('YmdHisu').rand(1,999).'.'.$uploadedFileExt;	//filename to be store
			move_uploaded_file($uploadfilename['tmp_name'], $uploadfile);				//move temp file from temp folder to upload folder
		}//eof if
		else
		{
			//if file not empty
			if($uploadfilename['name'])
				showNotificationError($uploadError);
		}//eof else
	}//eof if
	else if(is_array($uploadfilename['name']))
	{
		//count file upload
		$fileCount = count($uploadfilename['name']);
		
		//loop on file upload count
		for($x=0;$x<$fileCount;$x++)
		{
			//uploaded file extension
			$uploadedFileExt = getExtension($uploadfilename['name'][$x]);
			
			//default upload flag
			$allowUpload = false;
			
			//filter extension
			if(!$allowedExtension || array_search($uploadedFileExt, $allowedExtension) !== false)
			{
				$allowUpload = true;
				
				//filter filesize
				if($allowedSize)
				{
					//uploaded file size in kb
					$uploadedFileSize = $uploadfilename['size'][$x] / 1000;
				
					//file size bigger than max size
					if($uploadedFileSize > $allowedSize)
					{
						$allowUpload = false;
						$uploadError = $uploadfilename['name'][$x].' tidak dimuatnaik kerana saiz fail yang digunakan adalah tidak dibenarkan!';
					}//eof if
				}//eof if
			}//eof if
			else
				$uploadError = $uploadfilename['name'][$x].' tidak dimuatnaik kerana jenis fail yang digunakan adalah tidak dibenarkan!';
			
			//if allow to upload
			if($allowUpload)
			{
				$uploadfile[] = $ukploaddir.STC.'_'.date('YmdHis').rand(1,999).'.'.$uploadedFileExt;	//filename to be store
				move_uploaded_file($uploadfilename['tmp_name'][$x], $uploadfile[$x]);		//move temp file from temp folder to upload folder
			}//eof if
			else
			{
				$uploadfile[] = '';
				
				//if file not empty
				if($uploadfilename['name'][$x])
					showNotificationError($uploadError);
			}//eof else
		}//eof for
	}//eof else
	
	//return upload path
	return $uploadfile;
}

?>
