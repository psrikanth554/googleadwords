<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;

class GetAdGroups {

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
			->withClientCustomerId($_GET['c_id'])
			->build();
		self::runExample($session, $_GET['id'],@$_POST['from'],@$_POST['to']);
		 
	}

	 public static function runExample(AdWordsSession $session, $campaignId,$from=NULL,$to=NULL) {
		 if($from=="" || $to==""){ $during = 'LAST_7_DAYS'; } 
	else{ 
	 
		//$datef = str_replace('/', '-', $from);
		$dateFrom = date('Ymd', strtotime($from));
		
		//$datet = str_replace('/', '-', $to);
  		$dateTo = date('Ymd', strtotime($to));
 		$during = $dateFrom.",".$dateTo; 
		} 


		$reportQuery = 'select AdGroupId,AdGroupName,Clicks,Impressions,Cost,AverageCpc,AverageCost,CampaignStatus
					from ADGROUP_PERFORMANCE_REPORT
					where CampaignId = '.$campaignId.'
					during '.$during;

		// Download report as a string.
		$reportDownloader = new ReportDownloader($session);
		// Optional: If you need to adjust report settings just for this one
		// request, you can create and supply the settings override here. Otherwise,
		// default values from the configuration file (adsapi_php.ini) are used.
		$reportSettingsOverride = (new ReportSettingsBuilder())
		->includeZeroImpressions(true)
		->build();
		$reportDownloadResult = $reportDownloader->downloadReportWithAwql(
		$reportQuery, DownloadFormat::CSV, $reportSettingsOverride);
		// print "Report was downloaded and printed below:\n";
 		$data1 = explode("\n", $reportDownloadResult->getAsString());

		?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="assets/script.js"></script>
		<?php include('filter.php'); ?>
		 <pre>
		<table border=1 align="center">
		<tr>
		<?php if(isset($_POST['from']) && isset($_POST['to'])){ ?>
				<td colspan="13" align="center"> Results From <?php echo @$_POST['from']; ?> to <?php echo @$_POST['to']; ?></td>
		<?php }else{ ?>
				<td colspan="13" align="center"> Results From Last 7 days</td>
		<?php } ?>
	</tr>
			<?php $i=0;
			foreach ($data1 as $campaign) {
			 $data = explode(",", $campaign);

			 if($i==0) {}else if($i==1){ ?>
				<tr>
					<th><?php echo $data[0]; ?></th>
					<th><?php echo $data[1]; ?></th>
					<th><?php echo $data[2]; ?></th>
					<th><?php echo $data[3]; ?></th>
					<th>Target Cost</th>
					<th><?php echo $data[5]; ?></th>
					<th>Target CPO</th>
					<th>Target Revenue</th>
					<th>Target Leads</th>
					<th>Target Orders</th>
				</tr>
			<?php }else{ ?>
				<tr>
					<td><?php echo $data[0]; ?></td>
					<td><a href="keywordsapi.php?id=<?php  echo $data[0]; ?>&c_id=<?php  echo $_GET['c_id']; ?>"><?php echo $data[1]; ?></a></td>
					<td><?php echo $data[2]; ?></td>
					<td><?php echo $data[3]; ?></td>
					<td><?php echo round(($data[4]/1000000),2); ?></td>
					<td><?php echo  round(($data[5]/1000000),2); ?></td>
				</tr>
			<?php	//print_r($data);
				

				}
				if($i==(count($data1)-2)){  break; }
				$i++;
			}
 	  }
}
GetAdGroups::main();