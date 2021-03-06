<?php
include("config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdsInsightsFields;


$account = new AdAccount('act_'.$_GET['id']);
$adsets = $account->getCampaigns(array(
  CampaignFields::NAME,
   CampaignFields::STATUS
  
 ));

?>
<?php include('../filter.php'); 
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
		<th>Campaign Name</th>
		<th>Status</th>
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

foreach ($adsets as $adset) {
	/*echo $adset->{CampaignFields::ID}."<br>";
	echo $adset->{CampaignFields::NAME}."<br>";
	echo $adset->{CampaignFields::STATUS}."<br>";*/
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
			AdsInsightsFields::UNIQUE_CLICKS,
			AdsInsightsFields::REACH,
			AdsInsightsFields::SPEND,
			AdsInsightsFields::CPC,
			AdsInsightsFields::CTR,
		
	);

$insights = $adset->getInsights($fields, $params)->getLastResponse();
//print_r($insights->getContent()['data']);
?>

<tr>
		<td><?php  echo $adset->{CampaignFields::ID}; ?></td>
		<td><a href="adgroups.php?id=<?php  echo $adset->{CampaignFields::ID}; ?>"><?php  echo $adset->{CampaignFields::NAME}; ?></a></td>
		<td><?php echo $adset->{CampaignFields::STATUS}; ?></td>
 		<td><?php  echo @$insights->getContent()['data'][0]['impressions']; ?></td>
		<td><?php  echo @$insights->getContent()['data'][0]['unique_clicks']; ?></td>
  		<td>&#8377;<?php echo @$insights->getContent()['data'][0]['ctr']; ?></td>
		<td>&#8377;<?php echo @$insights->getContent()['data'][0]['cpc']; ?></td>
		<td>&#8377;<?php  echo @$insights->getContent()['data'][0]['spend']; ?></td>

		 
 	</tr>
<?php
}