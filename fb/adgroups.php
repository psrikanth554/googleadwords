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

echo "<pre>";
?>
<table border=1 align="center">
			<tr>
				<th>Id</th>
				<th>AdGroup Name</th>
				<th>Default Max CPC</th>
				<th>Clicks</th>
				<th>Impressions</th>
				<th>CTR</th>
				<th>Avg CPC</th>
				<th>Cost</th>
				<th>Group Type</th>
		 
			</tr>

<?php
foreach($adsets as $ad){
	//print_r($ad->getData()['name']); 
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
	$insights = $campaign1->getInsights($fields, $params)->getLastResponse();
	//print_r($insights->getContent()); 

	
	?>
	
	<tr>
				<td><?php  echo $ad->getData()['id']; ?></td>
				<td><a href="ads.php?id=<?php  echo $ad->getData()['id']; ?>"><?php  echo $ad->getData()['name']; ?></a></td>
				<td><?php  //echo $row['default_max_cpc']; ?></td>
				<td><?php  //echo $row['clicks']; ?></td>
				<td><?php  //echo $row['impressions']; ?></td>
				<td><?php //if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
				<td>&#8377;<?php  //echo $row['avg_cpc']; ?></td>
				<td>&#8377;<?php  echo $ad->getData()['daily_budget']; ?> </td>
				<td><?php  //echo $row['ad_group_type']; ?></td>
				 
			</tr>
			<?php

}
			?></table>