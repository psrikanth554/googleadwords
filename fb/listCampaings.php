<?php 

include("config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdsInsightsFields;

$fields = array(
  AdAccountFields::ID,
  AdAccountFields::NAME,
 );

$account = new AdAccount($account_id);
$cursor = $account->getCampaigns();
echo "<pre>";
?>
<table border=1 align="center">
	<tr>
		<th>Id</th>
		<th>Campaign Name</th>
 		<th>Campaign Budget</th>
		<th>Campaign Type</th>
 		<th>Bid Stratagy Type</th>
		<th>Impressions</th>
		<th>Clicks</th>
		<th>CTR</th>
		<th>Avg CPC</th>
		<th>Cost</th>
 
	</tr>

<?php
 foreach ($cursor as $campaign) {
	// print_r($campaign->getData());
	 $campaign1 = new Campaign($campaign->{CampaignFields::ID});
	 $campaign1->read(array(
		  CampaignFields::ID,
		  CampaignFields::NAME,
		  CampaignFields::OBJECTIVE,
		));

		//echo $campaign1->id;
		//echo "<br>";
		//echo $campaign1->name;
		//echo "<br>";
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

$insights = $campaign->getInsights($fields, $params)->getLastResponse();

//print_r($insights->getContent()['data']);

?>


 	<tr>
		<td><?php  echo $campaign1->id; ?></td>
		<td><a href="adgroups.php?id=<?php  echo $campaign1->id; ?>"><?php  echo $campaign1->name; ?></a></td>
 		<td>&#8377;<?php  //echo $row['campaign_budget']; ?></td>
		<td><?php  //echo $row['campaign_type']; ?></td>
		<td><?php  //echo //$row['bid_strategy_type']; ?></td>
		<td><?php  //echo //$row['impressions']; ?></td>
		<td><?php  //echo //$row['interaction']; ?></td>
		<td><?php //if($row['impressions']!=0){  echo round((($row['interaction']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
 		<td>&#8377;<?php  //echo $row['avg_cpc']; ?></td>
		<td><?php  //echo $row['cost']; ?></td>
		 
 	</tr>
<?php
 }
?>
</table>