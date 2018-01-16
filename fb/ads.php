<?php
include("config.php");
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdsInsightsFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\AdSet;
  


$adset = new AdSet($_GET['id']);
$ads = $adset->getAds(array(
	AdFields::NAME,
	AdFields::BID_AMOUNT,
));
//echo "<pre>";
?>
<table border=1 align="center">
			<tr>
				<th>Id</th>
				<th>Ad Name</th>
				<th>Default Max CPC</th>
				<th>Clicks</th>
				<th>Impressions</th>
				<th>CTR</th>
				<th>Avg CPC</th>
				<th>Cost</th>
				<th>Group Type</th>
		 
			</tr>

<?php
foreach($ads as $ad){
	// print_r($ad->getData()); ?>
	
	<tr>
				<td><?php  echo $ad->getData()['id']; ?></td>
				<td><a href="keywordsapi.php?id=<?php  echo $ad->getData()['id']; ?>"><?php  echo $ad->getData()['name']; ?></a></td>
				<td><?php  //echo $row['default_max_cpc']; ?></td>
				<td><?php  //echo $row['clicks']; ?></td>
				<td><?php  //echo $row['impressions']; ?></td>
				<td><?php //if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
				<td>&#8377;<?php  //echo $row['avg_cpc']; ?></td>
				<td>&#8377;<?php  //echo $row['cost']; ?></td>
				<td><?php  //echo $row['ad_group_type']; ?></td>
				 
			</tr>
			<?php
}
			?></table>