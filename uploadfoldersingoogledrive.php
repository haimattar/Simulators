<?php function send_google_drive($id,$fileno,$filename1,$filename2){
			global $wpdb;
			require(ABSPATH.'/wp-content/themes/enemat/googledrives/vendor/autoload.php');
			$client = getClient();
			$service = new Google_Service_Drive($client);
			if(!empty($filename1)){ 
				$results = $service->files->listFiles();
				foreach ($results->getFiles() as $item) {
					if ($item['name'] == 'ENEMAT CRM FILES') {
						$folderId = $item['id'];
						break;
					}
				}
				$parentid = $folderId;
				$childid = "";
				foreach ($results->getFiles() as $item) {
					if ($item['name'] == $fileno) {
						$childid = $item['id'];
						break;
					}
				}
				if(empty($childid)){
					$fileMetadata = new Google_Service_Drive_DriveFile(array(
										'name' => $fileno,
										'parents'=>array($parentid),
										'mimeType' => 'application/vnd.google-apps.folder'));
										$file = $service->files->create($fileMetadata, array(
										'fields' => 'id'));
					 $folderId = $file->id;
				 }else{
					$folderId = $childid;
				 }
					$newPermission = new Google_Service_Drive_Permission();
					$newPermission->setType('anyone');
					$newPermission->setRole('reader');
					$service->permissions->create($folderId, $newPermission);
					
					$fileMetadata = new Google_Service_Drive_DriveFile(array(
								'name' => array(basename($filename1)),
								'parents' => array($folderId)
							));
							$content = file_get_contents($filename1);
							$files = $service->files->create($fileMetadata, array(
									'data' => $content,
									'uploadType' => 'resumable',
									'fields' => 'id'));	
					$fileids = $files->id; 
					$docusignorgs = "https://drive.google.com/open?id=".$fileids."";
					$folderslink = "https://drive.google.com/drive/folders/".$folderId."";
					@unlink(ABSPATH."wp-content/themes/enemat/pdfs/".basename($filename1));
					$newPermission = new Google_Service_Drive_Permission();
					$newPermission->setType('anyone');
					$newPermission->setRole('reader');
					$service->permissions->create($fileids, $newPermission);
				 
			}
			 
			if(!empty($filename2)){ 
				$results = $service->files->listFiles();
				foreach ($results->getFiles() as $item) {
					if ($item['name'] == '46 - CONTRAT PARTENARIAT') {
						$folderId = $item['id'];
						break;
					}
				}
				 
					$fileMetadata = new Google_Service_Drive_DriveFile(array(
								'name' => array(basename($filename2)),
								'parents' => array($folderId)
							));
							$content = file_get_contents($filename2);
							$files = $service->files->create($fileMetadata, array(
									'data' => $content,
									'uploadType' => 'resumable',
									'fields' => 'id'));	
					$fileids1 = $files->id; 
					$contractdrivelink = "https://drive.google.com/open?id=".$fileids1."";
					$newPermission = new Google_Service_Drive_Permission();
					$newPermission->setType('anyone');
					$newPermission->setRole('reader');
					$service->permissions->create($fileids1, $newPermission);
					 
				 
			}
			 
		}
?>		
