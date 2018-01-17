<?php
include("config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdsInsightsFields;


$account = new AdAccount('act_1270889909671089');
$adsets = $account->getCampaigns(array(
  CampaignFields::NAME,
   CampaignFields::STATUS
  
 ));
echo "<pre>";
?>
<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>Campaign Name</th>
		<th>Status</th>
 		<th>Campaign Budget</th>
		<th>Impressions</th>
		<th>Clicks</th>
		<th>CTR</th>
		<th>Avg CPC</th>
		<th>Target Cost</th>
 
	</tr>

<?php

foreach ($adsets as $adset) {
	/*echo $adset->{CampaignFields::ID}."<br>";
	echo $adset->{CampaignFields::NAME}."<br>";
	echo $adset->{CampaignFields::STATUS}."<br>";*/
	$params = array(
			'time_range' => array(
			'since' => (new \DateTime("-1 week"))->format('Y-m-d'),
			'until' => (new \DateTime())->format('Y-m-d'),
			),
	);

	$fields = array(
			AdsInsightsFields::IMPRESSIONS,
			AdsInsightsFields::UNIQUE_CLICKS,
			AdsInsightsFields::REACH,
			AdsInsightsFields::SPEND,
	);

$insights = $account->getInsights($fields, $params)->getLastResponse();
 print_r($insights->getContent()['data']);
?>

<tr>
		<td><?php  echo $adset->{CampaignFields::ID}; ?></td>
		<td><a href="adgroups.php?id=<?php  echo $adset->{CampaignFields::ID}; ?>"><?php  echo $adset->{CampaignFields::NAME}; ?></a></td>
		<td><?php echo $adset->{CampaignFields::STATUS}; ?></td>
 		<td>&#8377;<?php  echo @$insights->getContent()['data'][0]['spend']; ?></td>
 		<td><?php  echo @$insights->getContent()['data'][0]['impressions']; ?></td>
		<td><?php  echo @$insights->getContent()['data'][0]['unique_clicks']; ?></td>
		<td><?php echo @$insights->getContent()['data'][0]['spend']; ?></td>
 		<td>&#8377;<?php  //echo $row['avg_cpc']; ?></td>
		<td><?php  //echo $row['cost']; ?></td>
		 
 	</tr>
<?php
}