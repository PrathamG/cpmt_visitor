<?php
/*
	controller.php
	This file contains all functions for the user which would alter data in the DB.
*/
	include 'db_conn.php';
	include 'model.php';

	session_start(); //Some functions verify that user is logged in.	

	//Verification used to determine which function to run. If the function is called through an AJAX request, a variable 'function' must also be sent with the request which contains the function name.
	if(isset($_REQUEST['function']))
	{
		if($_REQUEST['function'] == 'applyToPublicCampCounter')
		{
			applyToPublicCampCounter();
		}
		elseif($_REQUEST['function'] == 'applyToPublicCampDirect')
		{
			applyToPublicCampDirect();
		}
		elseif($_REQUEST['function'] == 'acceptPendingInvitation')
		{
			acceptPendingInvitation();
		}
		elseif($_REQUEST['function'] == 'rejectPendingInvitation')
		{
			rejectPendingInvitation();
		}
		elseif($_REQUEST['function'] == 'negotiatePendingInvitation')
		{
			negotiatePendingInvitation();
		}
	}
	elseif(isset($_POST['submitDraft']))
	{
		uploadDraft();
	}
	elseif(isset($_POST['submitReport']))
	{
		uploadReport();
	}

	function applyToPublicCampCounter()
	{
		global $mydb;

		$campId = $_REQUEST['campId'];
		$userId = $_REQUEST['userId'];
		$campaign = findCampById($campId);
		$brandPrice = getTierPrice($userId, $campaign);
		$influencerPrice = $_REQUEST['counter'];
		$message = $_REQUEST['message'];
		$thisTime = time();

		if(!preg_match('/^[0-9]{1,5}$/', $influencerPrice))
		{
			echo 2;
		}
		elseif(campIsPublic($campId) && !isRelated($campId, $userId) && $userId == $_SESSION['userId'])
		{
			$sql = "INSERT INTO jobs_influencer (campaignId, influencerId, createdAt, updatedAt, message, status, brandPrice, influencerPrice)
			VALUES ( ? , ? , $thisTime, $thisTime, ? , 0, ? , ?);";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("iisii", $campId, $userId, $message, $brandPrice, $influencerPrice);
			
			if($stmt->execute())
			{
				echo 1;
			}
			else
			{
				echo 'Error';
			}
		}
		else
		{
			echo 'Invalid action';
		}
	}

	function applyToPublicCampDirect()
	{
		global $mydb;

		$campId = $_REQUEST['campId'];
		$userId = $_REQUEST['userId'];
		$campaign = findCampById($campId);
		$price = getTierPrice($userId, $campaign);
		$thisTime = time();
		
		if(campIsPublic($campId) && !isRelated($campId, $userId) && ($userId == $_SESSION['userId']))
		{
			$sql = "INSERT INTO jobs_influencer (campaignId, influencerId, createdAt, updatedAt, status, brandPrice, influencerPrice)
			VALUES ( ? , ? , $thisTime, $thisTime, 0, ? , ?);";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("iiii", $campId, $userId, $price, $price);
			
			$stmt->execute();

			echo 1;
		}
		else
		{
			echo 'Invalid action';
		}
	}

	function acceptPendingInvitation()
	{
		global $mydb;

		$campId = $_REQUEST['campId'];
		$userId = $_REQUEST['userId'];

		if(isInvited($campId, $userId) && $userId == $_SESSION['userId'])
		{
			$sql = "SELECT brandPrice FROM jobs_influencer 
					WHERE campaignId = ? AND influencerId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("ii", $campId, $userId);
			$stmt->execute();

			$price = $stmt->get_result()->fetch_assoc()['brandPrice'];
			
			$thisTime = time();

			$sql = "UPDATE jobs_influencer
					SET status = 2, influencerPrice = $price, updatedAt = $thisTime
					WHERE campaignId = ? AND influencerId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("ii", $campId, $userId);

			if($stmt->execute())
			{
				echo 1;
			}
			else
			{
				echo 'Error';
			}
		}
		else
		{
			echo 'Invalid action';
		}
	}

	function rejectPendingInvitation()
	{
		global $mydb;

		$campId = $_REQUEST['campId'];
		$userId = $_REQUEST['userId'];

		if(isInvited($campId, $userId) && $userId == $_SESSION['userId'])
		{
			$sql = "DELETE FROM jobs_influencer WHERE influencerId = ? AND campaignId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("ii", $userId, $campId);

			if($stmt->execute())
			{
				echo 1;	
			}
			else
			{
				echo 'Error';
			}
		}
		else
		{
			echo 'Invalid action';
		}
	}

	function negotiatePendingInvitation()
	{
		global $mydb;

		$campId = $_REQUEST['campId'];
		$userId = $_REQUEST['userId'];
		$influencerPrice = $_REQUEST['influencerPrice'];

		if(!preg_match('/^\d{1,5}$/', $influencerPrice))
		{
			echo 2;
		}
		elseif(isInvited($campId, $userId) && $userId == $_SESSION['userId'])
		{
			$thisTime = time();
			$comment = $_REQUEST['comment'];
			$sql = "UPDATE jobs_influencer
					SET status = 0, influencerPrice = ?, updatedAt = $thisTime, message = ?
					WHERE campaignId = ? AND influencerId = ? ";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("isii", $influencerPrice, $comment, $campId, $userId);

			if($stmt->execute())
			{
				echo 1;	
			}
			else
			{
				echo 'Error';
			}
		}
		else
		{
			echo 'Invalid action';
		}
	}

	function uploadDraft()
	{
		global $mydb;

		$campId = $_POST['camp']; 
		$userId = $_POST['user'];
		$caption = urlencode($_POST['caption']);

		$sql = "SELECT status FROM jobs_influencer WHERE campaignId = ? AND influencerId =  ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $userId);
		$stmt->execute();
		if($stmt->get_result()->fetch_assoc()['status'] == 2 && $userId == $_SESSION['userId'])
		{
			$target_dir = "http://icm.cloudbreakr.com/uploads/";
			$target_file = $target_dir . $_POST['stamp'] . (basename($_FILES["draftUpload"]["name"]));
			$uploadOk = 1;
			$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			if(empty($caption))
			{
				echo 'Please enter a caption<br>';
				$uploadOk = 0;
			}			
		    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
			&& $fileType != "gif" && $fileType != "mp4" )
			{
			    echo "Only JPG, JPEG, PNG, GIF & MP4 files are allowed.<br>";
			    $uploadOk = 0;
			}
			else
			{
				if($fileType == "mp4" && filesize($_FILES["draftUpload"]["tmp_name"]) > 10485760)
				{
					echo 'Your video must be lower than 10MB.<br>';
					$uploadOk = 0;
				}
				elseif(filesize($_FILES["draftUpload"]["tmp_name"]) > 10485760)
				{
					echo 'Your image must be lower than 10MB.<br>';
					$uploadOk = 0;
				}
			}
					
			if ($uploadOk == 0)
			{
			    echo "Sorry, your file was not uploaded.";
			} 
			else
			{
				$sql = "INSERT INTO jobs_campaign_drafts (caption, draftPath)
						VALUES ( ? ,  ? ) ";
				$stmt = $mydb->prepare($sql);
				$stmt->bind_param('ss', $caption, $target_file);
				
			    if (move_uploaded_file($_FILES["draftUpload"]["tmp_name"], $target_file))
			    {
			        echo "The file ". basename( $_FILES["draftUpload"]["name"]). " has been uploaded.<br>";
			        if($stmt->execute())
			        {
			        	echo 'Draft Uploaded<br>';
			        }
			        else
			        {
			        	echo "Error";
			        }
			        $thisTime = time();
			        $sql = "UPDATE jobs_influencer
			        		SET draftId = $mydb->insert_id, status = 3, updatedAt = $thisTime
			        		WHERE campaignId = ? AND influencerId = ?";

			      	$stmt = $mydb->prepare($sql);
					$stmt->bind_param('ii', $campId, $userId);

			        if($stmt->execute()) //Redirect to completed_campaigns.php if Draft uploaded succesfully.
			        {
			        	echo "Path added to database.";
			        	header('Location: ../active_campaigns.php');
	 					die();
			        }
			        else
			        {
			        	echo "Error: Unable to add path";	
			        	die();
			        }
			    }
			    else 
			    {
			        echo "Sorry, there was an error uploading your file.";
			        die();
	    		}
			}
		}
		else
		{
			echo 'Invalid Action';
			die();
		}
		
	}

	function uploadReport()
	{
		global $mydb;

		$campaignId = $_POST['camp']; 
		$userId = $_POST['user'];

		$sql = "SELECT status FROM jobs_influencer WHERE campaignId = ? AND influencerId = ?";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('ii', $campaignId, $userId);
		$stmt->execute();

		if($stmt->get_result()->fetch_assoc()['status'] == 6 && $userId == $_SESSION['userId'])
		{
			$target_dir = "../reports/";
			$target_file = $target_dir . $_POST['stamp'] . basename($_FILES["reportUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			$check = getimagesize($_FILES["reportUpload"]["tmp_name"]);
		    if($check == false) 
		    {
		        echo "File is not an image.<br>";
		        $uploadOk = 0;
		    }
		    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg")
			{
			    echo "Only JPG, JPEG & PNG files are allowed.<br>";
			    $uploadOk = 0;
			}
			if(filesize($_FILES["reportUpload"]["tmp_name"]) > 10485760)
			{
				echo 'Please upload an image lower than 10MB<br>';
				$uploadOk = 0;
			}
			if ($uploadOk == 0) 
			{
			    echo "Sorry, your file was not uploaded.";
			} 
			else
			{
				if (move_uploaded_file($_FILES["reportUpload"]["tmp_name"], $target_file))
			    {
			        echo "The file ". basename( $_FILES["reportUpload"]["name"]). " has been uploaded.";
			        $thisTime = time();
					$sql = "UPDATE jobs_influencer
							SET reportPath = '$target_file', status = 7, updatedAt = $thisTime
							WHERE campaignId = ? AND influencerId = ?";

					$stmt = $mydb->prepare($sql);
					$stmt->bind_param('ii', $campaignId, $userId);

					if($stmt->execute()) //Redirect to completed_campaigns.php if Report uploaded succesfully.
					{
						echo '<br>Path added to database';
						header('Location: ../completed_campaigns.php'); 
					}
					else
					{
						echo 'error';
					}
				}
				else
				{
					echo 'Sorry, your file could not be sent.';
				}
			}
		}
		else
		{
			echo 'Invalid action';
		}
	}
?>