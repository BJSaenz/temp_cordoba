<?php
// This is a template for a PHP scraper on Morph (https://morph.io)
// including some code snippets below that you should find helpful
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$url = 'http://clima.bccba.com.ar/xmlDatosEstaciones.php';
$i = 1;
do {
	print "Processing page ".$i."\n";
	$list = new simple_html_dom();
	$list->load(scraperwiki::scrape($url.$i));
	
	$games = array();
	foreach($list->find('.teaser-content h2 a') as $link){
		$xml = new SimpleXMLElement(scraperwiki::scrape('http://www.boardgamegeek.com/xmlapi/search?search='.urlencode($link->plaintext).'&exact=1'));
		$games[] = $xml->boardgame['objectid'];
	}
	$xml = new SimpleXMLElement(scraperwiki::scrape('http://www.boardgamegeek.com/xmlapi2/thing?id='.implode(',', $games)));
	foreach($xml->item as $game){
		print $game->name['value']."\n";
	}
} while ($i < 1); //For testing a single page
//} while ($headers[0] == 'HTTP/1.1 200 OK');
print "Scraping complete.";
/*		
		$bggapi = new simple_html_dom();
		$bggapi->load(scraperwiki::scrape(''))
		
		$game = array();
		$game['name'] = filter_var($page->find('#title-area h1', 0)->plaintext, FILTER_SANITIZE_STRING);
		$game['year'] = preg_replace('/[^\b\d{4}\b]/', '', $page->find('#title-area .meta', 0)->plaintext);
		$game['url'] = $page->find('[rel=canonical]', 0)->href;
		
		$themes = $page->find('#gameinfo-default li', 0)->find('a');
		$mechanics = $page->find('#gameinfo-default li', 1)->find('a');
		if (is_object($page->find('#gameinfo-default li', 2))){
			$designers = $page->find('#gameinfo-default li', 2)->find('a');
			$game['designers'] = implode(',', array_map(function($theme){ return $theme->plaintext; }, $designers));
		}
		$game['themes'] = implode(',', array_map(function($theme){ return $theme->plaintext; }, $themes));
		$game['mechanics'] = implode(',', array_map(function($theme){ return $theme->plaintext; }, $mechanics));
		
		scraperwiki::save_sqlite(array('url'), $game);
	}
	$i++;
	$headers = get_headers($url.$i, 1);
*/
/*
for ($i = 1; $i <= 100; $i++){
	$html = scraperwiki::scrape("http://boardgamegeek.com/browse/boardgame/page/".$i);
	$games = array();
	
	$dom = new simple_html_dom();
	$dom->load($html);
	foreach($dom->find('td[class=collection_objectname] a') as $element) {
		$href = explode('/', $element->href);
		$id = $href[2];
		$name = $element->plaintext;
		
		print $element->plaintext .' '. $id ."\n";
		$game = array('BGG_ID' => $id, 'NAME' => $name);
		scraperwiki::save_sqlite(array('BGG_ID'), $game);
	}
	
}
*/
?>
