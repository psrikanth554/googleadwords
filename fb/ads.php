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
 include('../filter.php'); 
echo "<pre>";
//echo "<pre>";

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
				<th>Ad Name</th>
 				<th>Impressions</th>
				<th>Clicks</th>
				<th>CTR</th>
				<th>Avg CPC</th>
				<th>Target Cost</th>
 				<th>Target Leads</th>
				<th>Target Revenue</th>
				<th>Target Orders</th>

		 
			</tr>

<?php
foreach($ads as $ad){
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
	$insights = $ad->getInsights($fields, $params)->getLastResponse();
	 //print_r($insights->getContent());  
	  ?>
	
	<tr>
				<td><?php  echo $ad->getData()['id']; ?></td>
				<td><a href="keywordsapi.php?id=<?php  echo $ad->getData()['id']; ?>"><?php  echo $ad->getData()['name']; ?></a></td>
				<td><?php  echo @$insights->getContent()['data'][0]['impressions']; ?></td>
				<td><?php  echo @$insights->getContent()['data'][0]['unique_clicks']; ?></td>
				<td>&#8377;<?php echo @$insights->getContent()['data'][0]['ctr']; ?></td>
				<td>&#8377;<?php echo @$insights->getContent()['data'][0]['cpc']; ?></td>
				<td>&#8377;<?php  echo @$insights->getContent()['data'][0]['spend']; ?></td>
				 
			</tr>
			<?php
}
			?></table>