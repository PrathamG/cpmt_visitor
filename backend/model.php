<?php
	//model.php
	//File contains functions which retrieve data from the DB.
	function getMarketplace($location) //Returns array containing records of all campaigns in the marketplace.
	{
		global $mydb;
		$endDate = time()-(86400*360);
		$sql = "SELECT id, refId, facebook, youtube, instagram, title, businessId, createdAt, startDate, endDate, tier1Price, tier2Price, tier3Price
			FROM jobs_campaign 
			WHERE status = 'PUBLIC' AND isPrivate = 0 AND locationId = 1 AND acceptAt >= 1484571122 AND isRemoved IS null 
			ORDER BY createdAt DESC";

		$query = $mydb->query($sql);
		$public_campaigns = $query->fetch_all(MYSQLI_ASSOC);
		return $public_campaigns;
	}

	function findActiveCamp($id) //Returns array containing records of an influencer's active campaigns
	{
		global $mydb;
		$sql = "SELECT influencerId, campaignId, status, brandPrice, updatedAt, draftDate, postDate
				FROM jobs_influencer
				WHERE influencerId = ? AND status >= 2 AND status < 7
				ORDER BY updatedAt DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);
		if($selected_camp = $stmt->execute())
		{
			return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		}
		else
		{
			echo $mydb->error;
		}
	}

	function findPendingCamp($id) //Returns array containing records of an influencer's pending campaigns
	{
		global $mydb;
		$sql = "SELECT influencerId, campaignId, status, updatedAt
				FROM jobs_influencer
				WHERE influencerId = ? AND status < 2
				ORDER BY updatedAt DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);
		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		}
		else
		{
			echo $mydb->error;
		}
	}

	function findHomeCamp($id) //Returns array containing records of campaigns that require influencer action.
	{
		global $mydb;
		$sql = "SELECT influencerId, campaignId, status, updatedAt, draftDate, postDate
				FROM jobs_influencer
				WHERE influencerId = ? AND (status = 1 OR status = 2 OR status = 4 OR status = 6)
				ORDER BY updatedAt DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);
		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		}
		else
		{
			echo $mydb->error;
		}
	}

	function findCompleteCamp($id) //Returns array containing records of an influencer's completed campaigns
	{
		global $mydb;
		$sql = "SELECT campaignId, brandPrice FROM jobs_influencer 
				WHERE influencerId = ? AND status = 7
				ORDER BY updatedAt DESC";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);
		
		$stmt->execute();
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}


	function getAdminCamp() //Returns array containing records of all ongoing campaigns
	{
		global $mydb;
		$sql = "SELECT id, refId, facebook, youtube, instagram, title, businessId, createdAt, startDate, endDate, tier1Price, 				tier2Price, tier3Price FROM jobs_campaign
				WHERE locationId = 1 AND acceptAt >= 1515142779 AND isRemoved IS null 
				ORDER BY startDate ASC";

		if($allCamps = $mydb->query($sql)->fetch_all(MYSQLI_ASSOC))
		{
			return $allCamps;
		}
	}

	function getBrandById($id) 
	{
		global $mydb;

		$sql = 'SELECT profilePic, name, about, cpFirstName, cpLastName, businessTypeId FROM business_user WHERE id = ?';
		
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);

		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc();
		}
		else
		{
			echo $mydb->error;
			return 0;
		}
	}

	function getCampByRef($ref) //Get campaign details by reference Id
	{
		global $mydb;
		$ref = htmlspecialchars($ref);
		$sql = 	"SELECT productPhoto, id, link, about, refId, facebook, youtube, instagram, title, businessId, createdAt, startDate, endDate, status, tier1Price, tier3Price, tier2Price, totalBudget, locationId, contentGuide
				FROM jobs_campaign 
				WHERE refId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('s',$ref);

		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc();	
		}
		else
		{
			echo $mydb->error;
		}	
	}

	function findCampById($id) 
	{
		global $mydb;
		$sql = 	"SELECT productPhoto, id, link, about, refId, facebook, youtube, instagram, title, businessId, createdAt, startDate, endDate, status, tier1Price, tier2Price, tier3Price, locationId
				FROM jobs_campaign 
				WHERE id = ? ";
		
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i',$id);

		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc();
		}
		else
		{
			echo $mydb->error;
		}
	}

	function getStatusNumber($campId, $influencerId)
	{
		global $mydb;
		$sql = "SELECT status FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('ii', $campId, $influencerId);

		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc()['status'];
		}
		else
		{
			echo $mydb->error;
		}
	}

	function getCampType($id) //Returns the brand's type.
	{
		global $mydb;
		$sql = "SELECT name FROM business_type WHERE id = $id";
		if($obj = $mydb->query($sql))
		{
			$type = $obj->fetch_assoc()['name'];
			return $type;
		}
	}

	function getBarClassByStatus($campStatus, $barStatus) //Returns css class names for active campaigns progress bar.
	{
		if($campStatus == $barStatus)
		{
			return 'current-bar';
		}
		else if($campStatus > $barStatus)
		{
			return 'done-bar';
		}
	}

	function getActiveStatus($status) 
	{
		if($status == 2)
		{
			echo 'Draft Pending';
		}
		else if($status == 3)
		{
			echo 'Draft Review';
		}
		else if($status == 4)
		{
			echo 'Post Pending';
		}
		else if($status == 5)
		{
			echo 'Post Uploaded';
		}
		else if($status == 6)
		{
			echo 'Report Pending';
		}
	}

	function getPendingStatus($status)
	{
		if($status == 0)
		{
			echo 'Offer Sent';
		}
		else if($status == 1)
		{
			echo 'Offer Received';
		}
	}

	function getTierById($id)
	{
		global $mydb;
		$sql_fb = "SELECT fb_user.fanCount 
					FROM influencer
					LEFT JOIN fb_user ON influencer.fb_user_id = fb_user.id
					WHERE influencer.id = $id";
		$sql_ig = "SELECT ig_user.followerCount 
					FROM influencer 
					LEFT JOIN ig_user ON influencer.ig_user_id = ig_user.id
					WHERE influencer.id = $id";
		$fb_result = $mydb->query($sql_fb)->fetch_assoc();
		$ig_result = $mydb->query($sql_ig)->fetch_assoc();
		if(!isset($fb_result['fanCount']))
		{
			$fb_result['fanCount'] = 0;
		}
		if(!isset($ig_result['followerCount']))
		{
			$ig_result['followerCount'] = 0;
		}

		$totalFollowers = $fb_result['fanCount'] + $ig_result['followerCount'];
		
		$tier = 0;
		if($totalFollowers < 25000)
		{
			$tier = 3;
		}
		elseif($totalFollowers < 100000)
		{
			$tier = 2;
		}
		else
		{
			$tier = 1;
		}
		return $tier;

	}

	function getTierPrice($influencerId, $campaign) //Returns price offered by brand according to influencer's tier.
	{
		global $mydb;

		$tier = getTierById($influencerId);

		if($tier == 1)
		{
			return $campaign['tier1Price'];
		}
		elseif($tier == 2)
		{
			return $campaign['tier2Price'];
		}
		elseif($tier == 3)
		{
			return $campaign['tier3Price'];
		}
	}

	function getCampaignHashtag($campaign)
	{
		global $mydb;
		$sql = "SELECT hashtag FROM jobs_campaign_hashtags WHERE campaignId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $campaign['id']);

		$stmt->execute();
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}

	function getActivePrice($campId, $influencerId) //Returns the mutually agreed price for an influencer's active campaign.
	{
		global $mydb;
		$sql = "SELECT brandPrice FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $influencerId);
		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc()['brandPrice'];
		}
		else
		{
			return $mydb->error;
		}
	}

	function influencerIsPart($campaignId, $influencerId) 
	{
		global $mydb;
		$sql = "SELECT status FROM jobs_influencer WHERE campaignId = $campaignId AND influencerId = $influencerId";

		if($mydb->query($sql)->num_rows > 0)
		{
			return true;
		}
		return false;
	}

	function campIsPublic($id) //Checks whether campaign is public or private.
	{
		$market = getMarketplace($_SESSION['location']);
		foreach($market as $campaign)
		{
			if($id == $campaign['id'])
			{
				return true;
			}
		}
		return false;
	}

	function isRelated($campId, $user) //Checks if influencer is already associated with campaign.
	{
		global $mydb;
		$sql = "SELECT campaignId FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $user);
		$stmt->execute();
		$cid = $stmt->get_result();

		if($cid->num_rows > 0)
		{	
			$cid = $cid->fetch_assoc()['campaignId'];
			return true;
		}

		return false;
	}

	function getDeadlineDates($campId, $user)
	{
		global $mydb;
		$sql = "SELECT draftDate, postDate FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $user);
		if($stmt->execute())
		{
			$dates = $stmt->get_result()->fetch_assoc();
			return $dates;
		}
	}

	function isInvited($campId, $influencerId) //Checks if influencer has been invited to the respective campaign.
	{
		global $mydb;
		$sql = "SELECT status FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $influencerId);
		$stmt->execute();
		$status = $stmt->get_result()->fetch_assoc()['status'];

		if($status == 1)
		{
			return true;
		}
		return false;
	}
	
	function decodeSortString($code, $dir)
	{
		if($code === "status")
		{
			$col = "jobs_influencer.status";
		}
		elseif($code === "price")
		{
			$col = "jobs_influencer.brandPrice";
		}
		elseif($code === "inf_price")
		{
			$col = "jobs_influencer.influencerPrice";
		}
		elseif($code === "draft")
		{
			$col = "jobs_influencer.draftDate";
		}
		elseif($code === "post")
		{
			$col = "jobs_influencer.postDate";
		}
		elseif($code === "ig_follower")
		{
			$col = "ig_user.followerCount";
		}
		elseif($code === "ig_engagement")
		{
			$col = "ig_user.engagementRate";
		}
		elseif($code === "fb_follower")
		{
			$col = "fb_user.fanCount";
		}
		elseif($code === "fb_engagement")
		{
			$col = "fb_user.engagementRate";
		}
		elseif($code === "margin")
		{
			$col = "jobs_influencer.brandPrice-jobs_influencer.influencerPrice";
		}
		else
		{
			$col = "jobs_influencer.updatedAt";
		}

		if($dir === "asc")
		{
			$col .= " ASC";
		}
		elseif($dir === "desc")
		{
			$col .= " DESC";
		}
		

		return $col;
	}

	function getPendingInfluencers($campId, $sortBy = 'jobs_influencer.updatedAt DESC') //Returns array containing records of all pending influencers associated to a specific campaign.
	{
		if(isset($_GET['type']))
		{
			if($_GET['type'] == 'pending')
			{
				if(isset($_GET['sort']) && isset($_GET['dir']))
				{
					$sortBy = decodeSortString($_GET['sort'], $_GET['dir']);
				}
			}
		}

		global $mydb;

		$sql = "SELECT jobs_influencer.influencerId, jobs_influencer.campaignId, jobs_influencer.status, jobs_influencer.brandPrice, jobs_influencer.influencerPrice, jobs_influencer.message,
			influencer.name, influencer.ig_user_id, 
			fb_user.engagementRate, fb_user.fanCount, 
			ig_user.followerCount, ig_user.engagementRate as igEngagementRate

			FROM jobs_influencer
				INNER JOIN influencer ON jobs_influencer.influencerId = influencer.id
				LEFT JOIN fb_user ON influencer.fb_user_id = fb_user.id
				LEFT JOIN ig_user ON influencer.ig_user_id = ig_user.id

			WHERE jobs_influencer.campaignId = ? AND jobs_influencer.status < 2
			ORDER BY $sortBy";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $campId);

		if($stmt->execute())
		{
			$influencers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
			return $influencers;
		}
	}

	function getApprovedInfluencers($campId, $sortBy = 'jobs_influencer.updatedAt DESC') 
	{
		if(isset($_GET['type']))
		{
			if($_GET['type'] == 'approved')
			{
				if(isset($_GET['sort']) && isset($_GET['dir']))
				{
					$sortBy = decodeSortString($_GET['sort'], $_GET['dir']);
				}
			}
		}

		global $mydb;

		$sql = "SELECT jobs_influencer.influencerId, jobs_influencer.campaignId, jobs_influencer.status, jobs_influencer.brandPrice, jobs_influencer.draftId, jobs_influencer.draftDate, jobs_influencer.postDate, influencer.name, influencer.ig_user_id
		FROM jobs_influencer INNER JOIN influencer ON jobs_influencer.influencerId = influencer.id
		WHERE jobs_influencer.campaignId = ? AND jobs_influencer.status > 1 AND jobs_influencer.status < 7
		ORDER BY $sortBy";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $campId);

		if($stmt->execute())
		{
			$influencers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
			return $influencers;
		}
	}

	function getCompleteInfluencers($campId, $sortBy = "jobs_influencer.updatedAt DESC")
	{
		global $mydb;

		$sql = "SELECT jobs_influencer.influencerId, jobs_influencer.campaignId, jobs_influencer.status, jobs_influencer.brandPrice, jobs_influencer.reportPath, 
			influencer.name, influencer.ig_user_id
		FROM jobs_influencer INNER JOIN influencer ON jobs_influencer.influencerId = influencer.id
		WHERE jobs_influencer.campaignId = ? AND jobs_influencer.status = 7
		ORDER BY $sortBy";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $campId);

		if($stmt->execute())
		{
			$influencers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
			return $influencers;
		}
	}
	
	function getInfluencerById($id) 
	{
		global $mydb;
		$sql = "SELECT name, ig_user_id, fb_user_id, identityId, locationId, instagram, facebook, infPower FROM influencer WHERE id = ? AND name IS NOT null";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $id);

		if($stmt->execute())
		{
			$influencer = $stmt->get_result()->fetch_assoc();
			return $influencer;
		}
	}
	
	function getInfluencerIgById($id) //Returns influencer IG details
	{
		global $mydb;
		$igId = getInfluencerById($id)['ig_user_id'];
		
		if($igId === null)
		{
			$igId = 0;
		}

		$sql = "SELECT id, bio, followerCount, interaction, engagementRate, followerPercentage, interactionPercentage FROM ig_user WHERE id = $igId";
		$influencerIg = $mydb->query($sql)->fetch_assoc();
		return $influencerIg;
	}

	function getInfluencerFbById($id) //Returns influencer FB details
	{
		global $mydb;
		$fbId = $igId = getInfluencerById($id)['fb_user_id'];
		
		if($fbId === null)
		{
			$fbId = 0;
		}
		$sql = "SELECT id, username, fanCount, interaction, engagementRate, followerPercentage, interactionPercentage FROM fb_user WHERE id = $fbId";
		if($mydb->query($sql))
		{
			$influencerFb = $mydb->query($sql)->fetch_assoc();
			return $influencerFb;
		}
	}

	function getAdminStatus($status)
	{
		if($status === 0)
		{
			echo 'Applied';
		}
		else if($status == 1)
		{
			echo 'Invited';
		}
		else if($status == 2)
		{
			echo 'Draft Pending';
		}
		else if($status == 3)
		{
			echo 'Draft Review';
		}
		else if($status == 4)
		{
			echo 'Post Pending';
		}
		else if($status == 5)
		{
			echo 'Post Uploaded';
		}
		else if($status == 6)
		{
			echo 'Report Pending';
		}
		else if($status == 7)
		{
			echo 'Complete';
		}
	}

	function getLocationById($id)
	{
		if($id = 1)
		{
			echo 'Hong Kong';
		}
		elseif($id = 2)
		{
			echo 'Taiwan';
		}
		elseif($id = 3)
		{
			echo 'Malaysia';
		}
		elseif($id = 4)
		{
			echo 'Singapore';	
		}
	}

	function getInterestById($id) //Returns influencer interest category
	{
		global $mydb;
		$sql = "SELECT name FROM identity WHERE id = ?";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $id);
		if($stmt->execute())
		{
			return $stmt->get_result()->fetch_assoc()['name'];
		}
	}

	function draftIsSet($campId, $userId) //Checks if an earlier draft has already been submitted y influencer.
	{
		global $mydb;
		$sql = "SELECT draftId FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";

		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $userId);
		$stmt->execute();
		$draftId = $stmt->get_result()->fetch_assoc()['draftId'];
		if($draftId !== null)
		{
			return true;
		}
		return false;
	}

	function getDraftFeedback($campId, $userId) 
	{
		global $mydb;
		$sql = "SELECT draftId FROM jobs_influencer WHERE campaignId = ? AND influencerId = ? ";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $userId);
		$stmt->execute();
		$draftId = $stmt->get_result()->fetch_assoc()['draftId'];

		$sql = "SELECT feedback FROM jobs_campaign_drafts WHERE id = ? ";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("i", $draftId);
		$stmt->execute();
		$feedback = $stmt->get_result()->fetch_assoc()['feedback'];

		echo $feedback;
	}

	function brandFeedbackIsSet($campId, $userId)
	{
		global $mydb;
		$sql = "SELECT brandMessage FROM jobs_influencer WHERE campaignId = ? AND influencerId = ?";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ii", $campId, $userId);
		if($stmt->execute())
		{
			$msg = $stmt->get_result()->fetch_assoc()['brandMessage'];
			if($msg !== null)
			{
				return $msg;
			}	
			else
			{
				return false;
			}				
		}
	}

	function do_hash($str, $type = 'sha1') //Encrypts password input
	{
		if ( ! in_array(strtolower($type), hash_algos()))
		{
			$type = 'md5';
		}

		return hash($type, $str);
	}

	function getInfluencerPicByIgId($id) 
	{

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
			$dom_obj->loadHTML($page_content);
			$meta_val = null;

			foreach($dom_obj->getElementsByTagName('meta') as $meta)
			{
				if($meta->getAttribute('property')=='og:image')
				{ 
				    $meta_val = $meta->getAttribute('content');
				}
			}
			return $meta_val;
		}
		else{return 0;}
	}

	function getDbInfluencerPicByIgId($id)
	{
		global $mydb;

		$sql = "SELECT profilePic FROM ig_user WHERE id = $id";
		if($dp = $mydb->query($sql))
		{
			$dp = $dp->fetch_assoc()['profilePic'];
			echo urldecode($dp);
		}
		else
		{
			echo 'error';
		}
	}

	function setLastOnline($id) 
	{
		$thisTime = time();
		global $mydb;
		$sql = "SELECT user_id FROM influencer WHERE id = $id";
		$user = $mydb->query($sql)->fetch_assoc()['user_id'];

		$sql = "UPDATE user
				SET lastOnline = $thisTime
				WHERE id = $user";
		$mydb->query($sql);	
	}
	function isNotify($user, $updatedAt) //Checks if influencer should be notified about a specific campaign
	{
		global $mydb;
		$sql = "SELECT user_id FROM influencer WHERE id = $user";	
		$id = $mydb->query($sql)->fetch_assoc()['user_id'];
		$sql = "SELECT lastOnline FROM user WHERE id = $id";
		$time = $mydb->query($sql)->fetch_assoc()['lastOnline'];
		if($updatedAt > $time)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function getTopPostPicByIg($id)
	{
		global $mydb;
		$sql = "SELECT link FROM ig_pop_post WHERE ig_user_id = ? ORDER BY score DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$url = $stmt->get_result()->fetch_assoc()['link'];
		$url = urldecode($url).'?__a=1';
		$file = @file_get_contents($url);
		if($file !== false)
		{
			$data = json_decode($file, true);
			$link = $data['graphql']['shortcode_media']['display_url'];
			echo $link;
		}
		else
		{
			echo "image_not_found.jpg";
		}
	}

	function getTopPostPicByFb($id)
	{
		global $mydb;
		$sql = "SELECT link FROM fb_pop_post WHERE fb_user_id = ? ORDER BY score DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$url = $stmt->get_result()->fetch_assoc()['link'];
		$data = file_get_contents_curl($url);
		if($data !== false)
		{
			$html = new DOMDocument();
			libxml_use_internal_errors(true);
			$html->loadHTML($data);
			foreach($html->getElementsByTagName('meta') as $meta)
			{
				if($meta->getAttribute('property') == 'og:image')
				{
					echo $meta->getAttribute('content');
				}
			}
		}
		else
		{
			echo "image_not_found.jpg";
		}
	}

	function file_get_contents_curl($url)
 	{
	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36");
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

	    $data = curl_exec($ch);
	    curl_close($ch);

	    return $data;
	}

	function getTopPostByIg($id)
	{
		global $mydb;
		$sql = "SELECT content, likeCount, commentCount FROM ig_pop_post WHERE ig_user_id = ? ORDER BY score DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$topPost = $stmt->get_result()->fetch_assoc();
		return $topPost;
	}

	function getTopPostByFb($id)
	{
		global $mydb;
		$sql = "SELECT content, likeCount, commentCount FROM fb_pop_post WHERE fb_user_id = ? ORDER BY score DESC";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$topPost = $stmt->get_result()->fetch_assoc();
		return $topPost;
	}

	function decodeEmoji($text) 
	{
		if(!$text) return '';
		$text = $text[0];
		$decode = json_decode($text,true);
		if($decode) return $decode;
		$text = '["' . $text . '"]';
		$decode = json_decode($text);
		if(count($decode) == 1){
		   return $decode[0];
		}
		return $text;
	}

	function convertEmoji($text)
	{
		return preg_replace_callback('/(\\\u[0-9a-f]{4})+/', "decodeEmoji", $text);
	}

	function thousandsFormat($raw)
	{
        $PARTS = array('k', 'm', 'b', 't');

        if ($raw < 1000) {
            return $raw;
        }

        $numArray = explode(',', number_format(round($raw)));
        $thousandCount = count($numArray) - 1;
        $firstFloatingPoint = $numArray[1][0];
        $num = $numArray[0].((int) $firstFloatingPoint !== 0 ? '.'.$firstFloatingPoint : '');
        $part = $PARTS[$thousandCount - 1];
        return $num.$part;
    }

    function checkStatusMatch($influencerId, $campaignId, $statusMatch)
    {
    	global $mydb;
    	$sql = "SELECT status FROM jobs_influencer WHERE influencerId = ? AND campaignId = ?";
    	$stmt = $mydb->prepare($sql);
    	$stmt->bind_param('ii', $influencerId, $campaignId);
    	if($stmt->execute())
    	{
    		$currentStatus = $stmt->get_result()->fetch_assoc()['status'];
    		if($currentStatus == $statusMatch)
    		{
    			return true;
    		}
    		else
    		{
    			return false;
    		}
    	}
    	else
    	{
    		return false;
    	}
    }
?>