<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201710\cm\CampaignService;
 use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
 use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
 use Google\AdsApi\AdWords\ReportSettingsBuilder;
 use Google\AdsApi\AdWords\v201710\cm\Predicate;
use Google\AdsApi\AdWords\v201710\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201710\cm\OrderBy;
use Google\AdsApi\AdWords\v201710\cm\Paging;
 use Google\AdsApi\AdWords\v201710\cm\Selector;
use Google\AdsApi\AdWords\v201710\cm\SortOrder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
  
class GetCampaigns {

 const PAGE_LIMIT = 500;

  public static function main() {
    // Generate a refreshable OAuth2 credential for authentication.
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId($_GET['id'])
        ->build();
    self::runExample(new AdWordsServices(), $session);
  }
   public static function runExample(AdWordsServices $adWordsServices, AdWordsSession $session) {

		$campaignService = $adWordsServices->get($session, CampaignService::class);
 

		// Create selector.
		$selector = new Selector();
		$selector->setFields(['Id', 'Name','Status','Amount','Labels','StartDate','BiddingStrategyType','BiddingStrategyName','CampaignTrialType','TargetCpa','Level','BudgetId','BudgetName']);
		$selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
		$selector->setPaging(new Paging(0, self::PAGE_LIMIT));
		 $page = $campaignService->get($selector);

			 

  $reportQuery = 'select CampaignId,CampaignName,Clicks,Impressions,Cost,AverageCpc,Amount,AverageCpm,Ctr
					from CAMPAIGN_PERFORMANCE_REPORT
					during LAST_7_DAYS';
$reportDownloader = new ReportDownloader($session);
$reportSettingsOverride = (new ReportSettingsBuilder())
        ->includeZeroImpressions(true)
        ->build();
    $reportDownloadResult = $reportDownloader->downloadReportWithAwql(
        $reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
   // print "Report was downloaded and printed below:\n";
     // print $reportDownloadResult->getAsString();
	  $data1 = explode("\n", $reportDownloadResult->getAsString());
	  echo "<pre>";
  ?>

	<table border=1 align="center">
		
<?php $i=0;
foreach ($data1 as $campaign) {
 $data = explode(",", $campaign);

 if($i==0) {}else if($i==1){ ?>
	<tr>
		<th><?php echo $data[0]; ?></th>
		<th><?php echo $data[1]; ?></th>
		<th><?php echo $data[2]; ?></th>
		<th><?php echo $data[3]; ?></th>
		<th><?php echo $data[4]; ?></th>
		<th><?php echo $data[5]; ?></th>
		<th><?php echo $data[6]; ?></th>
		<th><?php echo $data[7]; ?></th>
		<th><?php echo $data[8]; ?></th>
  	</tr>
<?php }else{ ?>
	<tr>
		<td><?php echo $data[0]; ?></td>
		<td><a href="adgroupsapi.php?id=<?php  echo $data[0]; ?>&c_id=<?php  echo $_GET['id']; ?>"><?php echo $data[1]; ?></a></td>
		<td><?php echo $data[2]; ?></td>
		<td><?php echo $data[3]; ?></td>
		<td>&#8377;<?php echo round(($data[4]/1000000),2); ?></td>
		<td>&#8377;<?php echo  round(($data[5]/1000000),2); ?></td>
		<td>&#8377;<?php echo  round(($data[6]/1000000),2); ?>/day</td>
		<td>&#8377;<?php echo round(($data[7]/1000000),2); ?></td>
		<td><?php echo $data[8]; ?></td>
 	</tr>
<?php	//print_r($data);
	

	}
	if($i==(count($data1)-2)){  break; }
	$i++;
}
 
		/*foreach ($page->getEntries() as $campaign) {   //print_r($campaign->getBudget()->getAmount()->getMicroAmount()/1000000);

				$reportQuery = 'select CampaignId,CampaignName,Clicks,Impressions,Cost,AverageCpc
				from CAMPAIGN_PERFORMANCE_REPORT
				where CampaignId = '.$campaign->getId().'
				during LAST_7_DAYS';
				$reportDownloader = new ReportDownloader($session);
				$reportSettingsOverride = (new ReportSettingsBuilder())
											->includeZeroImpressions(false)
											->build();
				$reportDownloadResult = $reportDownloader->downloadReportWithAwql(
				$reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
				// print "Report was downloaded and printed below:\n";
				// print $reportDownloadResult->getAsString();
				$data = explode(",", $reportDownloadResult->getAsString());
				 //print_r($data);
   				?>
				<tr>
				<td><?php   echo $campaign->getId(); ?></td>
				<td><a href="adgroupsapi.php?id=<?php  echo $campaign->getId(); ?>&c_id=<?php  echo $_GET['id']; ?>"><?php  echo $campaign->getName(); ?></a></td>
				<td>&#8377;<?php   echo $campaign->getBudget()->getAmount()->getMicroAmount()/1000000; ?>/day</td>
				<td><?php  echo $campaign->getCampaignTrialType(); ?></td>
				<td><?php   echo $campaign->getStatus(); ?></td>
				<td><?php  echo $data[10]; ?></td>
				<td><?php  echo $data[9]; ?></td>
				<td><?php  if($data[10]!=0){  echo round((($data[9]/$data[10])*100),2)."%"; }else{ echo "0%"; }?></td>
				<td>&#8377;<?php $cpc = str_replace('total', '', $data[12]);   echo $cpc/1000000; ?></td>
				<td>&#8377;<?php  if(isset($data[11])) { echo round(($data[11]/1000000),1); }else{ echo "0";} ?></td>
				 
			</tr>
			<?php  
					//$i++; 
				 //if($i==3) { exit;  }
		}*/
		  
   }

}

GetCampaigns::main();