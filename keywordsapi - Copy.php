<?php 
namespace Google\AdsApi\Examples\AdWords\v201710\BasicOperations;
require __DIR__ . '/vendor/autoload.php';
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201710\cm\AdGroupCriterionService;
use Google\AdsApi\AdWords\v201710\cm\CriterionType;
use Google\AdsApi\AdWords\v201710\cm\OrderBy;
use Google\AdsApi\AdWords\v201710\cm\Paging;
use Google\AdsApi\AdWords\v201710\cm\Predicate;
use Google\AdsApi\AdWords\v201710\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201710\cm\Selector;
use Google\AdsApi\AdWords\v201710\cm\SortOrder;
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
    self::runExample(
        new AdWordsServices(), $session, $_GET['id']);
  }

public static function runExample(AdWordsServices $adWordsServices,
      AdWordsSession $session, $adGroupId) {
    $adGroupCriterionService =
        $adWordsServices->get($session, AdGroupCriterionService::class);
    // Create a selector to select all keywords for the specified ad group.
    $selector = new Selector();
    $selector->setFields(
        ['Id', 'CriteriaType', 'KeywordMatchType', 'KeywordText','CpcBid']);
    $selector->setOrdering([new OrderBy('KeywordText', SortOrder::ASCENDING)]);
    $selector->setPredicates([
        new Predicate('AdGroupId', PredicateOperator::IN, [$adGroupId]),
        new Predicate('CriteriaType', PredicateOperator::IN,
            [CriterionType::KEYWORD])
     ]);
    $selector->setPaging(new Paging(0, self::PAGE_LIMIT));
    $totalNumEntries = 0;

	$page = $adGroupCriterionService->get($selector);

	echo "<pre>";
	 print_r($page->getEntries()); ?>
	 <table border=1 align="center">
		<tr>
			<th>Id</th>
			<th>Keywords</th>
			<th>Max CPC</th>
			<th>Policy Details</th>
			<th>Final Url</th>
			<th>Clicks</th>
			<th>Impressions</th>
			<th>CTR</th>
			<th>Avg CPC</th>
			<th>Cost</th>
	  
		</tr>
 	 <?php
	  foreach ($page->getEntries() as $adGroupCriterion) { ?>
	  
	  <tr>
		<td><?php  echo $adGroupCriterion->getCriterion()->getId(); ?></td>
		<td><a href="#"><?php  echo $adGroupCriterion->getCriterion()->getText(); ?></a></td>
 		<td><?php  //echo $row['max_cpc']; ?></td>
		<td><?php // echo $row['policy_details']; ?></td>
		<td><?php // echo $row['final_url']; ?></td>
		<td><?php // echo $row['clicks']; ?></td>
 		<td><?php // echo $row['impressions']; ?></td>
		<td><?php //if($row['impressions']!=0){  echo round((($row['clicks']/$row['impressions'])*100),2)."%"; }else{ echo "0%"; }?></td>
  		<td>&#8377;<?php //if($row['impressions']!=0){  echo round((($row['cost']/$row['impressions'])),2)."%"; }else{ echo "0%"; }?></td>
		<td>&#8377;<?php // echo $row['cost']; ?></td>
 		 
 	</tr>
	  
	  <?php
}


}
}
GetKeywords::main();