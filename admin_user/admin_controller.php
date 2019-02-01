<?php
/*
	admin_controller.php
	Description: This file contains any functions used by the admin that would lead to changes in the database. 
*/
	include '../backend/db_conn.php';
	include '../backend/model.php';

	//Verification used to determine which function to run. If the function is run through an AJAX request, a variable 'function' must also be sent with the request which contains the function name.
	if(isset($_REQUEST['function']))
	{
		if($_REQUEST['function'] == 'adminAcceptApplication')
		{
			adminAcceptApplication();
		}
		if($_REQUEST['function'] == 'adminRejectApplication')
		{
			adminRejectApplication();
		}
		if($_REQUEST['function'] == 'adminCounterOffer')
		{
			adminCounterOffer();
		}
		if($_REQUEST['function'] == 'acceptDraft')
		{
			acceptDraft();
		}
		if($_REQUEST['function'] == 'rejectDraft')
		{
			rejectDraft();
		}
		if($_REQUEST['function'] == 'getInfluencerIgPic')
		{
			getInfluencerIgPic();
		}	
		if($_REQUEST['function'] == 'brandLiveSearch')
		{
			brandLiveSearch();
		}
		if($_REQUEST['function'] == 'setDraftDate')
		{
			setDraftDate();
		}
		if($_REQUEST['function'] == 'setPostDate')
		{
			setPostDate();
		}
		if($_REQUEST['function'] == 'inviteInfluencer')
		{
			inviteInfluencer();
		}
	}
	elseif(isset($_POST['submitGuide']))
	{
		uploadGuide();
	}

	function adminIsLogin()
	{
		session_start();
		if($_SESSION['adminUserId'] == 1350)
		{
			return true;
		}
		return false;
	}

	function adminAcceptApplication()
	{
		global $mydb;
		$influencers = $_POST['influencers'];
		$index = $_POST['index'];
		
		if(checkStatusMatch($influencers[$index]['influencerId'], $influencers[$index]['campaignId'], 0) && adminIsLogin())
		{
			$thisTime = time();
			$campId = $_POST['campId'];
			$influencerId = $influencers[$index]['influencerId'];
			$price = $influencers[$index]['influencerPrice'];

			$sql = "UPDATE jobs_influencer
					SET status = 2, brandPrice = ? , updatedAt = $thisTime
					WHERE campaignId = ? AND influencerId = ? ";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param('iii', $price, $campId, $influencerId);

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

	function adminRejectApplication()
	{
		global $mydb;
		$influencers = $_POST['influencers'];
		$index = $_POST['index'];
		
		if(checkStatusMatch($influencers[$index]['influencerId'], $influencers[$index]['campaignId'], 0) && adminIsLogin())
		{
			$campId = $_POST['campId'];
			$influencerId = $influencers[$index]['influencerId'];

			$sql = "DELETE FROM jobs_influencer
					WHERE campaignId = ? AND influencerId = ? ";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param('ii', $campId, $influencerId);

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

	function adminCounterOffer()
	{
		global $mydb;
		$influencers = $_POST['influencers'];
		$index = $_POST['index'];
		$price= $_POST['price'];
		
		if(!preg_match('/^[0-9]{1,5}$/', $price))
		{
			echo 2;
		}
		elseif(checkStatusMatch($influencers[$index]['influencerId'], $influencers[$index]['campaignId'], 0) && adminIsLogin())
		{
			$campId = $_POST['campId'];
			$message = $_POST['comment'];
			$influencerId = $influencers[$index]['influencerId'];
			$thisTime = time();

			$sql = "UPDATE jobs_influencer
					SET status = 1, brandPrice = ?, brandMessage = ?, updatedAt = $thisTime
					WHERE campaignId = ? AND influencerId = ? ";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param('isii', $price, $message, $campId, $influencerId);

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

	function acceptDraft()
	{
		global $mydb;
		$influencers = $_POST['influencers'];
		$index = $_POST['index'];
		
		if(checkStatusMatch($influencers[$index]['influencerId'], $influencers[$index]['campaignId'], 3) && adminIsLogin())
		{
			$campId = $_POST['campId'];
			$influencerId = $influencers[$index]['influencerId'];
			$thisTime = time();

			$sql = "UPDATE jobs_influencer
					SET status = 4, updatedAt = $thisTime
					WHERE campaignId = ? AND influencerId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param('ii', $campId, $influencerId);

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

	function rejectDraft()
	{
		global $mydb;
		$influencers = $_POST['influencers'];
		$index = $_POST['index'];
		
		if(checkStatusMatch($influencers[$index]['influencerId'], $influencers[$index]['campaignId'], 3) && adminIsLogin())
		{
			$campId = $_POST['campId'];
			$influencerId = $influencers[$index]['influencerId'];
			$draftId = $influencers[$index]['draftId'];
			$feedback = $_POST['feedback'];
			$thisTime = time();

			$sql = "UPDATE jobs_influencer
					SET status = 2, updatedAt = $thisTime
					WHERE campaignId = ? AND influencerId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param('ii', $campId, $influencerId);


			if($stmt->execute())
			{
				$sql = "UPDATE jobs_campaign_drafts
						SET feedback = ?
						WHERE id = ?";
				$stmt = $mydb->prepare($sql);
				$stmt->bind_param('si', $feedback, $draftId);
				if($stmt->execute())
				{
					echo 1;
				}
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

	function getInfluencerIgPic()
	{
		$id = $_POST['id'];
		global $mydb;

		$sql = "SELECT username FROM ig_user WHERE id = ?";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i', $id);

		if($stmt->execute())
		{
			$username = $stmt->get_result()->fetch_assoc()['username'];
		}
		
		$url = 'https://www.instagram.com/' . $username;
		$page_content = @file_get_contents($url);
		if($page_content !== FALSE)
		{

			$dom_obj = new DOMDocument();
			libxml_use_internal_errors(true);
			$dom_obj->loadHTML($page_content);
			libxml_use_internal_errors(false);
			$meta_val = null;

			foreach($dom_obj->getElementsByTagName('meta') as $meta)
			{
				if($meta->getAttribute('property')=='og:image')
				{ 
				    $meta_val = $meta->getAttribute('content');
				}
			}
			echo $meta_val;
		}
		else
		{
			return 0;
		}
	}

	function uploadGuide()
	{
		global $mydb;
		$targetfolder = "content_guide/";
 		$targetfolder = $targetfolder . time() . basename( $_FILES['uploadGuide']['name']);
 		$campId = $_POST['cId'];
		$file_type=$_FILES['uploadGuide']['type'];

		if ($file_type=="application/pdf")
		{
			if(move_uploaded_file($_FILES['uploadGuide']['tmp_name'], '../' . $targetfolder))
			{
				echo "The file ". basename( $_FILES['uploadGuide']['name']). " is uploaded<br>";
				$sql = "UPDATE jobs_campaign
						SET contentGuide = '$targetfolder' WHERE id = ? ";
				$stmt = $mydb->prepare($sql);
				$stmt->bind_param('i', $campId);
				if($stmt->execute())
				{
					echo 'Path added to database';
				}
				else
				{
					echo 'An error occured. File could not be added to db';
				}
			}
			else 
			{
				echo "Problem uploading file";
			}
		}
		else 
		{
			echo "You may only upload PDF Files.";
		}
	}

	function brandLiveSearch()
	{
		$input = $_POST['search'];
		global $mydb;
		$pattern = $input . '%';
		$sql = "SELECT jobs_campaign.id, jobs_campaign.refId, jobs_campaign.facebook, jobs_campaign.youtube, jobs_campaign.instagram, jobs_campaign.title, jobs_campaign.businessId, jobs_campaign.createdAt, jobs_campaign.startDate, jobs_campaign.endDate, business_user.name 
				FROM jobs_campaign
					INNER JOIN business_user
						ON jobs_campaign.businessId = business_user.id
				WHERE jobs_campaign.locationId = 1 
					AND jobs_campaign.acceptAt >= 1500006482 
					AND jobs_campaign.isRemoved IS null
					AND business_user.name LIKE ?
				ORDER BY startDate ASC";
		if($stmt = $mydb->prepare($sql))
		{
			$stmt->bind_param('s', $pattern);
			$stmt->execute();
			$allCamps = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
			if(!empty($allCamps))
			{
				echo json_encode($allCamps);
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			echo 0;
		}
	}

	function setDraftDate()
	{
		if(adminIsLogin())
		{
			global $mydb;

			$date = $_REQUEST['date'];
			$influencerId = $_REQUEST['infId'];
			$campaignId = $_REQUEST['campaignId'];

			$sql = "UPDATE jobs_influencer SET draftDate = ? WHERE influencerId = ? AND campaignId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("iii", $date, $influencerId, $campaignId);
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

	function setPostDate()
	{
		if(adminIsLogin())
		{
			global $mydb;

			$date = $_REQUEST['date'];
			$influencerId = $_REQUEST['infId'];
			$campaignId = $_REQUEST['campaignId'];

			$sql = "UPDATE jobs_influencer SET postDate = ? WHERE influencerId = ? AND campaignId = ?";

			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("iii", $date, $influencerId, $campaignId);
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

	function inviteInfluencer()
	{
		if(adminIsLogin())
		{
			global $mydb;
			$influencerId = $_REQUEST['infId'];
			$campaignId = $_REQUEST['campaignId'];
			$campaign = findCampById($campaignId);
			$tierPrice = getTierPrice($influencerId, $campaign);
			$time = time();
			$sql = "INSERT INTO jobs_influencer (campaignId, influencerId, createdAt, updatedAt, status, brandPrice)
					VALUES(?, ?, $time, $time, 1, ?)";
			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("iii", $campaignId, $influencerId, $tierPrice);
			if($stmt->execute())
			{
				echo 1;
			}
			else
			{
				echo "Error";
			}
		}
		else
		{
			echo 'Invalid action';
		}	
	}
?>