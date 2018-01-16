<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\Reporting;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201710\ReportDownloader;
use Google\AdsApi\AdWords\Reporting\v201710\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;

class DownloadCriteriaReportWithAwql {

	public static function main() {
    // Generate a refreshable OAuth2 credential for authentication.
    $oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();

    // See: AdWordsSessionBuilder for setting a client customer ID that is
    // different from that specified in your adsapi_php.ini file.
    // Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
		->withClientCustomerId(2790163755)
        ->build();
     self::runExample($session, DownloadFormat::CSV);
  }

  public static function runExample(AdWordsSession $session, $reportFormat) {

	   $filePath = sprintf(
        '%s.csv',
        tempnam(sys_get_temp_dir(), 'criteria-report-')
    );
    // Create report query to get the data for last 7 days.
    $reportQuery = 'select ExternalCustomerId,CanManageClients
					from ACCOUNT_PERFORMANCE_REPORT';

    // Download report as a string.
    $reportDownloader = new ReportDownloader($session);
    // Optional: If you need to adjust report settings just for this one
    // request, you can create and supply the settings override here. Otherwise,
    // default values from the configuration file (adsapi_php.ini) are used.
    $reportSettingsOverride = (new ReportSettingsBuilder())
        ->includeZeroImpressions(true)
        ->build();
    $reportDownloadResult = $reportDownloader->downloadReportWithAwql(
        $reportQuery, $reportFormat, $reportSettingsOverride);
   // print "Report was downloaded and printed below:\n";
    echo "<pre>";
	  print_r($reportDownloadResult->getAsString());
     // print $reportDownloadResult->getAsString();
	  //$data = explode("\n", $reportDownloadResult->getAsString());
	  //echo "<pre>";
	  //print_r($data);
	 //$reportDownloadResult->saveToFile($filePath);

	/*$reportQuery = 'SELECT CampaignId,CampaignName, Impressions, Clicks, Ctr , ConversionRate '
                    . ' FROM CAMPAIGN_PERFORMANCE_REPORT '
                    . ' WHERE CampaignId = '.$id.' DURING '.$date.','.$date;

        // Download report.
       /* ReportUtils::DownloadReportWithAwql($reportQuery, $filePath, $user,$reportFormat, $options); 
        $dataArray =file($filePath);
        foreach ($dataArray as $value) {
          $data = explode(",", $value);
          $resultat[$i-1]['jours'] = $date;
          $resultat[$i-1]['impressions'] = $data[2];
          $resultat[$i-1]['clicks'] = $data[3];
          $resultat[$i-1]['taux_click'] = $data[4];
          $resultat[$i-1]['taux_conversion'] = $data[5];
        }
    echo json_encode($resultat);*/
  }
}

DownloadCriteriaReportWithAwql::main();
