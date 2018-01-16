<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;

require __DIR__ . '/vendor/autoload.php';

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201710\cm\AdGroupService;
use Google\AdsApi\AdWords\v201710\cm\OrderBy;
use Google\AdsApi\AdWords\v201710\cm\Predicate;
use Google\AdsApi\AdWords\v201710\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201710\cm\Paging;
use Google\AdsApi\AdWords\v201710\cm\Selector;
use Google\AdsApi\AdWords\v201710\cm\SortOrder;
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
		self::runExample(
			new AdWordsServices(), $session, $_GET['id']);
		 
	}

	 public static function runExample(AdWordsServices $adWordsServices, AdWordsSession $session, $campaignId) {
		$adGroupService = $adWordsServices->get($session, AdGroupService::class);

		// Create a selector to select all ad groups for the specified campaign.
		$selector = new Selector();
		$selector->setFields(['Id', 'Name','TargetCpa','Status']);
		$selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
		$selector->setPredicates(
			[new Predicate('CampaignId', PredicateOperator::IN, [$campaignId])]);
		$selector->setPaging(new Paging(0, self::PAGE_LIMIT));
		echo "<pre>";
 		 $page = $adGroupService->get($selector);
		//print_r($page->getEntries());
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
			foreach ($page->getEntries() as $adGroup) { ?>

			<tr>
				<td><?php  echo $adGroup->getId(); ?></td>
				<td><a href="keywordsapi.php?id=<?php  echo $adGroup->getId(); ?>&c_id=<?php  echo $_GET['c_id']; ?>"><?php  echo $adGroup->getName(); ?></a></td>
				<td><?php  //echo $row['default_max_cpc']; ?></td>
				<td><?php  //echo $row['clicks']; ?></td>
				<td><?php  //echo $row['impressions']; ?></td>
				<td><?php //if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
				<td>&#8377;<?php  //echo $row['avg_cpc']; ?></td>
				<td>&#8377;<?php  //echo $row['cost']; ?></td>
				<td><?php  //echo $row['ad_group_type']; ?></td>
				 
			</tr>

			<?php }
	  }
}
GetAdGroups::main();