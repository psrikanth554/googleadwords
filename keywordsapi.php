<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;
require __DIR__ . '/vendor/autoload.php';
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;

class GetKeywords {
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
    self::runExample($session, $_GET['id']);
  }

public static function runExample(AdWordsSession $session, $adGroupId) {
     $reportQuery = 'select AdGroupId,DisplayName,Clicks,Impressions,Cost,AverageCpc,AverageCost,CampaignStatus
					from CRITERIA_PERFORMANCE_REPORT
					where AdGroupId = '.$adGroupId.'
					during LAST_7_DAYS';

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
		echo "<pre>";
		$data1 = explode("\n", $reportDownloadResult->getAsString());

	echo "<pre>";
	 //print_r($page->getEntries()); ?>
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
GetKeywords::main();