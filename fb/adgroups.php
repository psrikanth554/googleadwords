<?php
include("config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdsInsightsFields;

$campaign1 = new Campaign($_GET['id']);
$adsets = $campaign1->getAdSets(array(
			  AdSetFields::NAME,
			  AdSetFields::START_TIME,
			  AdSetFields::END_TIME,
			  AdSetFields::DAILY_BUDGET,
			  AdSetFields::LIFETIME_BUDGET,
			));

 include('../filter.php'); 
echo "<pre>";
?>
<table border=1 align="center">
<tr>
	<?php if(isset($_POST['from']) && isset($_POST['to'])){ ?>
		<td colspan="13" align="center"> Results From <?php echo @$_POST['from']; ?> to <?php echo @$_POST['to']; ?></td>
	<?php }else{ ?>
		<td colspan="13" align="center"> Results From Last 7 days</td>
	<?php } ?>
	</tr>
			<tr>
				<th>Id</th>
				<th>AdGroup Name</th>
 				<th>Impressions</th>
				<th>Clicks</th>
				<th>CTR</th>
				<th>CPC</th>
				<th>Target Cost</th>
 				<th>Target CPO</th>
				<th>Target Leads</th>
				<th>Target Revenue</th>
				<th>Target Orders</th>
		 
			</tr>

<?php
foreach($adsets as $ad){
	//print_r($ad->getData()['name']); 
	 if(@$_POST['from']=="" || @$_POST['to']==""){ $since = (new \DateTime("-1 week"))->format('Y-m-d'); $until = (new \DateTime())->format('Y-m-d'); } 
	else{ 
		 //$since = $from;
		 $since = date('Y-m-d', strtotime($_POST['from']));
		 $until = date('Y-m-d', strtotime($_POST['to']));
		} 
	 	$params = array(
			'time_range' => array(
			'since' => $since,
			'until' => $until,
			),
	);

		$fields = array(
							AdsInsightsFields::IMPRESSIONS,
							AdsInsightsFields::CLICKS,
							AdsInsightsFields::REACH,
							AdsInsightsFields::SPEND,
							AdsInsightsFields::CPC,
							AdsInsightsFields::CTR,
						);
	$insights = $ad->getInsights($fields, $params)->getLastResponse();
	// print_r($insights->getContent()); 

	
	?>
	
	<tr>
				<td><?php  echo $ad->getData()['id']; ?></td>
				<td><a href="ads.php?id=<?php  echo $ad->getData()['id']; ?>"><?php  echo $ad->getData()['name']; ?></a></td>
 				<td><?php  echo @$insights->getContent()['data'][0]['impressions']; ?></td>
				<td><?php  echo @$insights->getContent()['data'][0]['clicks']; ?></td>
				<td>&#8377;<?php echo @$insights->getContent()['data'][0]['ctr']; ?></td>
				<td>&#8377;<?php echo @$insights->getContent()['data'][0]['cpc']; ?></td>
				<td>&#8377;<?php  echo @$insights->getContent()['data'][0]['spend']; ?></td>
				 
			</tr>
			<?php

}
			?></table>