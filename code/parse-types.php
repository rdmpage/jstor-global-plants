<?php

require_once(dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');


$filename = '2.html';

$html = file_get_contents($filename);

$dom = str_get_html($html);

$divs = $dom->find('div[class=result-item]');
foreach ($divs as $div)
{
	$obj = new stdclass;
	$obj->uuid = $div->{'data-id'};
	
	foreach ($div->find('img[class=thumb]') as $img)
	{
		$obj->thumbnailUrl = 'http://plants.jstor.org' . $img->src;
	}
	
	foreach ($div->find('h3[class=title] a') as $a)
	{
		$obj->title = $a->plaintext;
		$obj->id = $a->href;
		$obj->id = 'http://plants.jstor.org' . preg_replace('/\?(.*)$/', '', $obj->id);
	}

	foreach ($div->find('img[class=thumb]') as $img)
	{
		$obj->thumbnailUrl = 'http://plants.jstor.org' . $img->src;
	}
           
    $keys = array("collector","date","resource_type","country","herbarium","names");
    foreach ($keys as $k)
    {
		foreach ($div->find('div[class=' . $k . ']') as $element)
		{
			foreach ($element->find('span[class=metadata-label]') as $span)
			{
				$key = $span->plaintext;
			}
			$value = $element->outertext;
			
			$value = preg_replace('/^(.*)<\/span>\s+/u', '', $value);
			$value = preg_replace('/\s+<\/div>/u', '', $value);
			$value = preg_replace('/<br\/>/u', '', $value);
			
			
			$value = trim($value);

			$obj->{$key} = $value;

		}
	}
	
	print_r($obj);
}

?>

