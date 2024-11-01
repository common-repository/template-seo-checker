<?php 

/**
 * Get HTML code from url passed by param
 * @param $url
 * @return unknown_type
 */
function jja_tsc_file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function jja_tsc_get_file_size($url) {
	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $data = curl_exec($ch);
    curl_close($ch);
    
	if(preg_match('/Content-Length: (\d+)/', $data, $matches)) {
		return (int)$matches[1];
    } else {
    	return 0;
    }
}

function jja_tsc_inline_stlyes($data) {
	return substr_count($data, 'style=');
}

function jja_tsc_number_css($data) {
	return substr_count($data, '.css');
}

function jja_tsc_number_js($data) {
	return substr_count($data, '.js');
}

function jja_tsc_css_for_print($data) {
	$n = substr_count($data, 'media="print"');
	$m = substr_count($data, "media='print'");
	return (($n+$m) > 0) ? $n+$m : 0;
}

function jja_tsc_is_html5($data) {
	return substr_count($data, '<!doctype html>');
}

function jja_tsc_dom_elements($data) {
	return substr_count($data, '<');
}

function jja_tsc_parse_html($data, &$result) {
	$num_links_with_title = 0;
	$num_images_with_alt = 0;
	$num_images_with_height = 0;
	$images_size = 0;
	$css_size = 0;
	$js_size = 0;

	$html = str_get_html($data);

	$num_links = $html->find('a');
	foreach($html->find('a[title]') as $a) {
		$num_links_with_title++;
	}

	$num_images = $html->find('img');
	foreach($html->find('img[alt]') as $img) {
		$num_images_with_alt++;
	}
	foreach($html->find('img[height]') as $img) {
		$num_images_with_height++;
	}
	foreach($num_images as $img) {
		$images_size += jja_tsc_get_file_size($img->getAttribute('src'));
	}

	$num_titles = $html->find('title');
	$result['title'] = isset($num_titles[0]) ? $num_titles[0]->plaintext : '';
	
	$num_metas = $html->find('meta');
	$result['description'] = '';
	$result['robots'] = '';
	foreach($num_metas as $meta) {
		if($meta->getAttribute('name') == 'description') {
			$result['description'] = $meta->getAttribute('content');
		}
		if($meta->getAttribute('name') == 'robots') {
			$result['robots'] = $meta->getAttribute('content');
		}
	}
	
	$media_queries = 0;
	$html_css = $html->find('link[rel]');
	foreach($html_css as $css) {
		if($css->getAttribute('type') == 'text/css') {
			$url_css = $css->getAttribute('href');
			if(!empty($url_css)) {
				$current_css = jja_tsc_file_get_contents_curl($url_css);
				$num_media_queries = substr_count(strtolower($current_css), '@media');
				if($num_media_queries > 0) {
					$media_queries = 1;
					break;
				}
			}
		}
	}
	
	foreach($html_css as $css) {
		if($css->getAttribute('type') == 'text/css') {
			$url_css = $css->getAttribute('href');
			if(!empty($url_css)) {
				$css_size += jja_tsc_get_file_size($url_css);
			}
		}
	}
	
	$html_js = $html->find('script');
	foreach($html_js as $js) {
		$src = $js->getAttribute('src');
		if(!empty($src)) {
			$js_size += jja_tsc_get_file_size($src);
		}
	}

	$result['links_without_title'] = (count($num_links) - $num_links_with_title);
	$result['images_without_alt'] = (count($num_images) - $num_images_with_alt);
	$result['images_without_height'] = (count($num_images) - $num_images_with_height);
	$result['image_size'] = $images_size;
	$result['css_size'] = $css_size;
	$result['js_size'] = $js_size;
	$result['is_site_responsive'] = $media_queries;
}

function jja_tsc_get_headings($data, &$result) {
	$result['headings_num_h1'] = substr_count($data, '<h1');
	$result['headings_num_h2'] = substr_count($data, '<h2');
	$result['headings_num_h3'] = substr_count($data, '<h3');
	$result['headings_num_h4'] = substr_count($data, '<h4');
	$result['headings_num_h5'] = substr_count($data, '<h5');
	$result['headings_num_h6'] = substr_count($data, '<h6');
}

function jja_tsc_html5_tags($data) {
	$num_html5_tags = 0;
	$num_html5_tags += substr_count($data, '<canvas');
	$num_html5_tags += substr_count($data, '<audio');
	$num_html5_tags += substr_count($data, '<embed');
	$num_html5_tags += substr_count($data, '<source');
	$num_html5_tags += substr_count($data, '<track');
	$num_html5_tags += substr_count($data, '<video');
	$num_html5_tags += substr_count($data, '<datalist');
	$num_html5_tags += substr_count($data, '<keygen');
	$num_html5_tags += substr_count($data, '<output');
	$num_html5_tags += substr_count($data, '<article');
	$num_html5_tags += substr_count($data, '<aside');
	$num_html5_tags += substr_count($data, '<bdi');
	$num_html5_tags += substr_count($data, '<details');
	$num_html5_tags += substr_count($data, '<dialog');
	$num_html5_tags += substr_count($data, '<figcaption');
	$num_html5_tags += substr_count($data, '<figure');
	$num_html5_tags += substr_count($data, '<footer');
	$num_html5_tags += substr_count($data, '<header');
	$num_html5_tags += substr_count($data, '<main');
	$num_html5_tags += substr_count($data, '<mark');
	$num_html5_tags += substr_count($data, '<menuitem');
	$num_html5_tags += substr_count($data, '<meter');
	$num_html5_tags += substr_count($data, '<nav');
	$num_html5_tags += substr_count($data, '<progress');
	$num_html5_tags += substr_count($data, '<rp');
	$num_html5_tags += substr_count($data, '<rt');
	$num_html5_tags += substr_count($data, '<ruby');
	$num_html5_tags += substr_count($data, '<section');
	$num_html5_tags += substr_count($data, '<summary');
	$num_html5_tags += substr_count($data, '<time');
	$num_html5_tags += substr_count($data, '<wbr');
	return $num_html5_tags;
}

function jja_tsc_get_seo_score_by_url($url) {
	// Parsing url dom...
	$result = array();
	$data = strtolower(jja_tsc_file_get_contents_curl($url));
	jja_tsc_parse_html($data, $result);
	$tscUrlErrorsModel = jja_tsc_urls_errors_model::get_instance();
	$tscUrlErrorsModel->delete_url_error($url);
	
	$errors_url = array();
	if(!empty($result['robots'])) {
		if(substr_count($result['robots'], 'noindex') > 0) {
			$error_robots = array('url' => $url, 'id_error' => 1, 'num_elements' => $result['robots'], 'comments' => __('Looks like <strong>your template is blocking access to robots</strong>. Your page won\'t be indexed at Google. No one will see your page at <abbr title="Search Engine Result Pages">SERP</abbr> when searches something at Google that you wrote about.', 'tsc'), 'score' => 0);
			$tscUrlErrorsModel->insert_url_error($error_robots);
			$errors_url[] = $error_robots;
		} else{ 
			$errors_url = jja_tsc_analize_seo_score($data, $result, $url);
		}
	} else {
		$errors_url = jja_tsc_analize_seo_score($data, $result, $url);
	}
	
	return $errors_url;
}

function jja_tsc_analize_seo_score($data, $result, $url) {
	$tscUrlErrorsModel = jja_tsc_urls_errors_model::get_instance();
	
	// Inline styles..
	$inline_styles = jja_tsc_inline_stlyes($data);
	$inline_styles_comments = '';
	if($inline_styles == 0) {
		$inline_styles_score = 100;
		$inline_styles_comments = __('<strong>Perfect</strong>! no inline styles were found at current URL.', 'tsc');
	}
	if($inline_styles == 1) {
		$inline_styles_score = 90;
		$inline_styles_comments = __('<strong>Well!</strong> a few inline styles were found at current URL.', 'tsc');
	}
	if($inline_styles == 2) {
		$inline_styles_score = 80;
		$inline_styles_comments = __('<strong>Well!</strong> a few inline styles were found at current URL.', 'tsc');
	}
	if($inline_styles == 3) {
		$inline_styles_score = 70;
		$inline_styles_comments = __('<strong>Well!</strong> a few inline styles were found at current URL.', 'tsc');
	}
	if($inline_styles == 4) {
		$inline_styles_score = 60;
		$inline_styles_comments = __('<strong>Oops!</strong> there were found inline styles at current URL. To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles == 5) {
		$inline_styles_score = 50;
		$inline_styles_comments = __('<strong>Oops!</strong> there were found inline styles at current URL. To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles == 6) {
		$inline_styles_score = 40;
		$inline_styles_comments = __('<strong>Ooppps!</strong> too much inline styles found at current URL.To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles == 7) {
		$inline_styles_score = 30;
		$inline_styles_comments = __('<strong>Ooppps!</strong> too much inline styles found at current URL.To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles >= 8 && $inline_styles <= 10) {
		$inline_styles_score = 20;
		$inline_styles_comments = __('<strong>Ooppps!</strong> too much inline styles found at current URL.To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles >= 10 && $inline_styles < 16) {
		$inline_styles_score = 10;
		$inline_styles_comments = __('<strong>Ooppps!</strong> too much inline styles found at current URL.To optimize your page try to include these styles in your CSS.', 'tsc');
	}
	if($inline_styles >= 16) {
		$inline_styles_score = 0;
		$inline_styles_comments = __('That\'s <strong>Incredible!</strong> Do you know about CSS? Your current URL is full of inline styles. You must put it on external CSS files, please do it.', 'tsc');
	}
	$errors_inline = array('url' => $url, 'id_error' => 2, 'num_elements' => $inline_styles, 'comments' => $inline_styles_comments, 'score' => $inline_styles_score);
	$tscUrlErrorsModel->insert_url_error($errors_inline);
	$errors_url[] = $errors_inline;
		
	// Site responsive
	$errors_responsive = array('url' => $url, 'id_error' => 3, 'num_elements' => $result['is_site_responsive'], 'comments' => '', 'score' => ($result['is_site_responsive']) ? 100 : 0);
	$errors_url[] = $errors_responsive;
	$tscUrlErrorsModel->insert_url_error($errors_responsive);
		
	// Headings evaluation
	jja_tsc_get_headings($data, $result);
	$is_html5 = jja_tsc_is_html5($data);
	$score_headings = 100;
	$heading_comment = array();
		
	$headings = array(
			sprintf(__('<strong>%d</strong> &lt;h1&gt; tags were found.', 'tsc'), $result['headings_num_h1']),
			sprintf(__('<strong>%d</strong> &lt;h2&gt; tags were found.', 'tsc'), $result['headings_num_h2']),
			sprintf(__('<strong>%d</strong> &lt;h3&gt; tags were found.', 'tsc'), $result['headings_num_h3']),
			sprintf(__('<strong>%d</strong> &lt;h4&gt; tags were found.', 'tsc'), $result['headings_num_h4']),
			sprintf(__('<strong>%d</strong> &lt;h5&gt; tags were found.', 'tsc'), $result['headings_num_h5']),
			sprintf(__('<strong>%d</strong> &lt;h6&gt; tags were found.', 'tsc'), $result['headings_num_h6'])
	);
		
	if(!$is_html5 && $result['headings_num_h1'] > 1) {
		$score_headings -= 50;
		$heading_comment[] = __('Current page\'s doctype is not HTML5, this means that your page can\'t have more than &lt;h1&gt; tag. Please, fix it.', 'tsc');
	} else if($result['headings_num_h1'] == 0) {
		$score_headings -= 50;
		$heading_comment[] = __('No &lt;h1&gt; tag was found at current URL.', 'tsc');
	} else if(($result['headings_num_h2'] + $result['headings_num_h3'] + $result['headings_num_h4'] + $result['headings_num_h5'] + $result['headings_num_h6']) == 0) {
		$score_headings -= 100;
		$heading_comment[] = __('No heading tags were found at current URL. You can check the importance of these tags at <a href="wp-admin/admin.php?page=template-seo-checker&viewfaq=1">FAQ page</a>.', 'tsc');
	} else if($result['headings_num_h2'] > 6) {
		$score_headings -= 20;
		$heading_comment[] = __('Excessive number of &lt;h2&gt; tags were found.', 'tsc');
	} else if($result['headings_num_h3'] > 6) {
		$score_headings -= 20;
		$heading_comment[] = __('Excessive number of &lt;h3&gt; tags were found.', 'tsc');
	} else if(($result['headings_num_h2'] + $result['headings_num_h3'] + $result['headings_num_h4'] + $result['headings_num_h5'] + $result['headings_num_h6']) > 15) {
		$score_headings -= 30;
		$heading_comment[] = __('Excessive number of heading tags were found.', 'tsc');
	} else if(
	($result['headings_num_h2'] == 0 && ($result['headings_num_h3'] + $result['headings_num_h4'] + $result['headings_num_h5'] + $result['headings_num_h6']) > 0) ||
	($result['headings_num_h3'] == 0 && ($result['headings_num_h4'] + $result['headings_num_h5'] + $result['headings_num_h6']) > 0) ||
	($result['headings_num_h4'] == 0 && ($result['headings_num_h5'] + $result['headings_num_h6']) > 0) ||
	($result['headings_num_h5'] == 0 && $result['headings_num_h6'] > 0)) {
		$score_headings -= 10;
		$heading_comment[] = __('Check your headings tags for current URL, you\'re including tags in wrong order (for example you are using &lt;h5&gt; without using &lt;h4&gt;)', 'tsc');
	}
	$score_headings = ($score_headings < 0) ? 0 : $score_headings;
	$errors_headings = array('url' => $url, 'id_error' => 4, 'num_elements' => $result['headings_num_h1'] + $result['headings_num_h2'] + $result['headings_num_h3'] + $result['headings_num_h4'] + $result['headings_num_h5'] + $result['headings_num_h6'],
			'comments' => implode('--', $headings).'||'.implode('--', $heading_comment),
			'score' => $score_headings);
	$errors_url[] = $errors_headings;
	$tscUrlErrorsModel->insert_url_error($errors_headings);
		
	// Images size
	$image_size_score = 0;
	$comments_image_size = '';
	if($result['image_size'] <= 102400) {
		$image_size_score = 100;
		$comments_image_size = __('Perfect, the size in Kb of images for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['image_size'] > 102400 && $result['image_size'] <= 153600) {
		$image_size_score = 90;
		$comments_image_size = __('Perfect, the size in Kb of images for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['image_size'] > 153600 && $result['image_size'] <= 256000) {
		$image_size_score = 80;
		$comments_image_size = __('The size in Kb of images for current URL it\'s pretty good.', 'tsc');
	}
	if($result['image_size'] > 256000 && $result['image_size'] <= 307200) {
		$image_size_score = 70;
		$comments_image_size = __('The size in Kb of images for current URL it\'s pretty good.', 'tsc');
	}
	if($result['image_size'] > 307200 && $result['image_size'] <= 512000) {
		$image_size_score = 60;
		$comments_image_size = __('The size in Kb of images for current URL it\'s pretty good.', 'tsc');
	}
	if($result['image_size'] > 512000 && $result['image_size'] <= 614400) {
		$image_size_score = 50;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['image_size'] > 614400 && $result['image_size'] <= 716800) {
		$image_size_score = 40;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['image_size'] > 716800 && $result['image_size'] <= 768000) {
		$image_size_score = 30;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['image_size'] > 768000 && $result['image_size'] <= 819200) {
		$image_size_score = 20;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['image_size'] > 819200 && $result['image_size'] <= 870400) {
		$image_size_score = 10;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['image_size'] > 870400 && $result['image_size'] <= 921600) {
		$image_size_score = 5;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['image_size'] > 921600) {
		$image_size_score = 0;
		$comments_image_size = __('The size in Kb of images for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	$errors_image_size = array('url' => $url, 'id_error' => 5, 'num_elements' => $result['image_size'], 'comments' => $comments_image_size, 'score' => $image_size_score);
	$errors_url[] = $errors_image_size;
	$tscUrlErrorsModel->insert_url_error($errors_image_size);
		
	// Number of css...
	$number_of_css = jja_tsc_number_css($data);
	$comments_number_of_css = '';
	if($number_of_css < 2) $number_css_score = 100;
	if($number_of_css == 2) $number_css_score = 95;
	if($number_of_css == 3) $number_css_score = 80;
	if($number_of_css == 4) $number_css_score = 60;
	if($number_of_css == 5) {
		$number_css_score = 50;
		$comments_number_of_css = __('Your theme is including five CSS files. Can you reduce it?', 'tsc');
	}
	if($number_of_css > 5 && $number_of_css < 8) {
		$number_css_score = 40;
		$comments_number_of_css = __('Your theme is including 6-8 CSS files. Can you reduce it?', 'tsc');
	}
	if($number_of_css >= 8 && $number_of_css < 10) {
		$number_css_score = 30;
		$comments_number_of_css = __('Your theme is including too much CSS files. Are you using too many plugins maybe?', 'tsc');
	}
	if($number_of_css >= 10 && $number_of_css < 12) {
		$number_css_score = 10;
		$comments_number_of_css = __('Your theme is including too much CSS files. Are you using too many plugins maybe?', 'tsc');
	}
	if($number_of_css >= 12) {
		$number_css_score = 0;
		$comments_number_of_css = __('Your theme is including insane amount of CSS files. Fix it now.', 'tsc');
	}
	$errors_num_css = array('url' => $url, 'id_error' => 6, 'num_elements' => $number_of_css, 'comments' => $comments_number_of_css, 'score' => $number_css_score);
	$errors_url[] = $errors_num_css;
	$tscUrlErrorsModel->insert_url_error($errors_num_css);
		
	// Number of js
	$number_of_js = jja_tsc_number_js($data);
	$comments_number_of_js = '';
	if($number_of_js < 2) $number_of_js_score = 100;
	if($number_of_js == 2) $number_of_js_score = 95;
	if($number_of_js == 3) $number_of_js_score = 80;
	if($number_of_js == 4) $number_of_js_score = 60;
	if($number_of_js == 5) {
		$number_of_js_score = 50;
		$comments_number_of_js = __('Your teme is including five JS files. Can you reduce it?', 'tsc');
	}
	if($number_of_js > 5 && $number_of_js < 8) {
		$number_of_js_score = 40;
		$comments_number_of_js = __('Your theme is including 6-8 JS files. Can you reduce it?', 'tsc');
	}
	if($number_of_js >= 8 && $number_of_js < 10) {
		$number_of_js_score = 30;
		$comments_number_of_js = __('Your theme is including too much JS files. Are you using too many plugins maybe?', 'tsc');
	}
	if($number_of_js >= 10 && $number_of_js < 12) {
		$number_of_js_score = 10;
		$comments_number_of_js = __('Your theme is including too much JS files. Are you using too many plugins maybe?', 'tsc');
	}
	if($number_of_js >= 12) {
		$number_of_js_score = 0;
		$comments_number_of_js = __('Your theme is including insane amount of JS files. Fix it now.', 'tsc');
	}
	$errors_num_js = array('url' => $url, 'id_error' => 7, 'num_elements' => $number_of_js, 'comments' => $comments_number_of_js, 'score' => $number_of_js_score);
	$errors_url[] = $errors_num_js;
	$tscUrlErrorsModel->insert_url_error($errors_num_js);
		
	// Title
	$title_score = 100; $title_message = '';
	$title_length = strlen($result['title']);
	if(empty($result['title'])) {
		$title_score = 0;
		$title_message = __('Your theme has not &lt;title&gt; tag or is empty.', 'tsc');
	}
	if($title_length < 5) {
		$title_score = 40;
		$title_message = __('Your &lt;title&gt; tag is too short.', 'tsc');
	}
	if($title_length >= 5 && $title_length <= 10) {
		$title_score = 50;
		$title_message = __('Your &lt;title&gt; tag is too short.', 'tsc');
	}
	if($title_length > 10 && $title_length <= 40) {
		$title_score = 70;
	}
	if($title_length > 40 && $title_length <= 60) {
		$title_score = 90;
	}
	if($title_length > 60) {
		$title_score = 80;
		$title_message = __('Your &lt;title&gt; tag is too long.', 'tsc');
	}
	$errors_title = array('url' => $url, 'id_error' => 8, 'num_elements' => $result['title'], 'comments' => $title_message, 'score' => $title_score);
	$errors_url[] = $errors_title;
	$tscUrlErrorsModel->insert_url_error($errors_title);
		
	// Description
	$description_score = 100; $description_message = '';
	$desc_length = strlen($result['description']);
	if(empty($result['description'])) {
		$description_score = 0;
		$description_message = __('Meta name description tag is empty.', 'tsc');
	} else {
		if($desc_length < 20) {
			$description_score = 40;
			$description_message = __('Meta name description tag is too short.', 'tsc');
		}
		if($desc_length >= 20 && $desc_length <= 100) {
			$description_score = 70;
		}
		if($desc_length > 100 && $desc_length <= 155) {
			$description_score = 90;
		}
		if($desc_length > 155) {
			$description_score = 80;
			$description_message = __('Meta name description tag is too long.', 'tsc');
		}
	}
	$errors_desc = array('url' => $url, 'id_error' => 9, 'num_elements' => $result['description'], 'comments' => $description_message, 'score' => $description_score);
	$errors_url[] = $errors_desc;
	$tscUrlErrorsModel->insert_url_error($errors_desc);
		
	// CSS Size
	$css_size_score = 0;
	$comments_css_size = '';
	if($result['css_size'] <= 102400) {
		$css_size_score = 100;
		$comments_css_size = __('Perfect, the size in Kb of CSS files for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['css_size'] > 102400 && $result['css_size'] <= 153600) {
		$css_size_score = 90;
		$comments_css_size = __('Perfect, the size in Kb of CSS files for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['css_size'] > 153600 && $result['css_size'] <= 256000) {
		$css_size_score = 80;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['css_size'] > 256000 && $result['css_size'] <= 307200) {
		$css_size_score = 70;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['css_size'] > 307200 && $result['css_size'] <= 512000) {
		$css_size_score = 60;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['css_size'] > 512000 && $result['css_size'] <= 614400) {
		$css_size_score = 50;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['css_size'] > 614400 && $result['css_size'] <= 716800) {
		$css_size_score = 40;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['css_size'] > 716800 && $result['css_size'] <= 768000) {
		$css_size_score = 30;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['css_size'] > 768000 && $result['css_size'] <= 819200) {
		$css_size_score = 20;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['css_size'] > 819200 && $result['css_size'] <= 870400) {
		$css_size_score = 10;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['css_size'] > 870400 && $result['css_size'] <= 921600) {
		$css_size_score = 5;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['css_size'] > 921600) {
		$css_size_score = 0;
		$comments_css_size = __('The size in Kb of CSS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	$errors_css_size = array('url' => $url, 'id_error' => 10, 'num_elements' => $result['css_size'], 'comments' => $comments_css_size, 'score' => $css_size_score);
	$errors_url[] = $errors_css_size; 
	$tscUrlErrorsModel->insert_url_error($errors_css_size);
		
	// JS Size
	$js_size_score = 0;
	$comments_js_size = '';
	if($result['js_size'] <= 102400) {
		$js_size_score = 100;
		$comments_js_size = __('Perfect, the size in Kb of JS files for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['js_size'] > 102400 && $result['js_size'] <= 153600) {
		$js_size_score = 90;
		$comments_js_size = __('Perfect, the size in Kb of JS files for current URL is lower than 150Kb.', 'tsc');
	}
	if($result['js_size'] > 153600 && $result['js_size'] <= 256000) {
		$js_size_score = 80;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['js_size'] > 256000 && $result['js_size'] <= 307200) {
		$js_size_score = 70;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['js_size'] > 307200 && $result['js_size'] <= 512000) {
		$js_size_score = 60;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s pretty good.', 'tsc');
	}
	if($result['js_size'] > 512000 && $result['js_size'] <= 614400) {
		$js_size_score = 50;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['js_size'] > 614400 && $result['js_size'] <= 716800) {
		$js_size_score = 40;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['js_size'] > 716800 && $result['js_size'] <= 768000) {
		$js_size_score = 30;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['js_size'] > 768000 && $result['js_size'] <= 819200) {
		$js_size_score = 20;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 500Kb. Try to reduce it.', 'tsc');
	}
	if($result['js_size'] > 819200 && $result['js_size'] <= 870400) {
		$js_size_score = 10;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['js_size'] > 870400 && $result['js_size'] <= 921600) {
		$js_size_score = 5;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	if($result['js_size'] > 921600) {
		$js_size_score = 0;
		$comments_js_size = __('The size in Kb of JS files for current URL it\'s above 800Kb. Try to reduce it, your SEO score can be affected.', 'tsc');
	}
	$errors_js_size = array('url' => $url, 'id_error' => 11, 'num_elements' => $result['js_size'], 'comments' => $comments_js_size, 'score' => $js_size_score);
	$errors_url[] = $errors_js_size; 
	$tscUrlErrorsModel->insert_url_error($errors_js_size);
		
	// Links without title
	$links_title_score = 0;
	$comments_links_title = '';
	switch($result['links_without_title']) {
		case 0 :
			$links_title_score = 100;
			$comments_links_title = __('Perfect, no links without title were found.', 'tsc');
			break;
		case 1 :
			$links_title_score = 90;
			$comments_links_title = __('Pretty good, just a few links without title were found.', 'tsc');
			break;
		case 2 :
			$links_title_score = 80;
			$comments_links_title = __('Pretty good, just a few links without title were found.', 'tsc');
			break;
		case 3 :
			$links_title_score = 70;
			$comments_links_title = __('Pretty good, just a few links without title were found.', 'tsc');
			break;
		case 4 :
			$links_title_score = 60;
			$comments_links_title = __('A few number of links without title were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 5 :
			$links_title_score = 50;
			$comments_links_title = __('A few number of links without title were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 6 :
			$links_title_score = 40;
			$comments_links_title = __('You should include attribute title for all your links to get a best SEO score.', 'tsc');
			break;
		case 7 :
			$links_title_score = 30;
			$comments_links_title = __('You should include attribute title for all your links to get a best SEO score.', 'tsc');
			break;
		default :
			$links_title_score = 0;
			$comments_links_title = __('You must include attribute title for all your links. You can rank better if you do.', 'tsc');
			break;
	}
	$errors_link_title = array('url' => $url, 'id_error' => 12, 'num_elements' => $result['links_without_title'], 'comments' => $comments_links_title, 'score' => $links_title_score);
	$errors_url[] = $errors_link_title;
	$tscUrlErrorsModel->insert_url_error($errors_link_title);
		
	// Images without alt
	$images_alt_score = 0;
	$comments_images_alt = '';
	switch($result['images_without_alt']) {
		case 0 :
			$images_alt_score = 100;
			$comments_images_alt = __('Perfect, no images without alt were found.', 'tsc');
			break;
		case 1 :
			$images_alt_score = 90;
			$comments_images_alt = __('Pretty good, just a few images without alt were found.', 'tsc');
			break;
		case 2 :
			$images_alt_score = 80;
			$comments_images_alt = __('Pretty good, just a few images without alt were found.', 'tsc');
			break;
		case 3 :
			$images_alt_score = 70;
			$comments_images_alt = __('Pretty good, just a few images without alt were found.', 'tsc');
			break;
		case 4 :
			$images_alt_score = 60;
			$comments_images_alt = __('A few number of images without alt were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 5 :
			$images_alt_score = 50;
			$comments_images_alt = __('A few number of images without alt were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 6 :
			$images_alt_score = 40;
			$comments_images_alt = __('You should include attribute alt for all your images to get a best SEO score.', 'tsc');
			break;
		case 7 :
			$images_alt_score = 30;
			$comments_images_alt = __('You should include attribute alt for all your images to get a best SEO score.', 'tsc');
			break;
		default :
			$images_alt_score = 0;
			$comments_images_alt = __('You must include attribute alt for all your images. You can rank better if you do.', 'tsc');
			break;
	}
	$errors_image_alt = array('url' => $url, 'id_error' => 13, 'num_elements' => $result['images_without_alt'], 'comments' => $comments_images_alt, 'score' => $images_alt_score);
	$errors_url[] = $errors_image_alt;
	$tscUrlErrorsModel->insert_url_error($errors_image_alt);
	
	// Number of dom elements
	$result['dom_elements'] = jja_tsc_dom_elements($data);
	$dom_score = 0;
	if($result['dom_elements'] < 600) $dom_score = 100;
	else if($result['dom_elements'] >= 600 && $result['dom_elements'] < 2000) $dom_score = 80;
	else if($result['dom_elements'] >= 2000 && $result['dom_elements'] < 3000) $dom_score = 50;
	else if($result['dom_elements'] >= 3000 && $result['dom_elements'] < 5000) $dom_score = 30;
	else $dom_score = 10;
	
	$errors_dom_elements = array('url' => $url, 'id_error' => 14, 'num_elements' => $result['dom_elements'], 'comments' => '', 'score' => $images_alt_score);
	$errors_url[] = $errors_dom_elements;
	$tscUrlErrorsModel->insert_url_error($errors_dom_elements);
		
	// html5 ready
	if($is_html5) {
		$score_html5_ready = 50;
		$num_html5_tags_used = jja_tsc_html5_tags($data);
		if($num_html5_tags_used > 0 && $num_html5_tags_used < 4) {
			$score_html5_ready += 10;
		} else if($num_html5_tags_used >= 4 && $num_html5_tags_used <= 10) {
			$score_html5_ready += 30;
		} else if($num_html5_tags_used > 10 && $num_html5_tags_used <= 20) {
			$score_html5_ready += 10;
		}
		// We should also check for rich snippets implementation
		$errors_html5_ready = array('url' => $url, 'id_error' => 15, 'num_elements' => $num_html5_tags_used, 'comments' => '', 'score' => $score_html5_ready);
		$errors_url[] = $errors_html5_ready;
		$tscUrlErrorsModel->insert_url_error($errors_html5_ready);
	} else {
		$errors_html5_ready = array('url' => $url, 'id_error' => 15, 'num_elements' => 0, 'comments' => '', 'score' => 0);
		$errors_url[] = $errors_html5_ready;
		$tscUrlErrorsModel->insert_url_error($errors_html5_ready);
	}
		
	// images without height
	$images_height_score = 0;
	$comments_images_height = '';
	switch($result['images_without_height']) {
		case 0 :
			$images_height_score = 100;
			$comments_images_height = __('Perfect, no images without height and width attributes were found.', 'tsc');
			break;
		case 1 :
			$images_height_score = 90;
			$comments_images_height = __('Pretty good, just a few images without alt height and width attributes found.', 'tsc');
			break;
		case 2 :
			$images_height_score = 80;
			$comments_images_height = __('Pretty good, just a few images without alt height and width attributes found.', 'tsc');
			break;
		case 3 :
			$images_height_score = 70;
			$comments_images_height = __('Pretty good, just a few images without alt height and width attributes found.', 'tsc');
			break;
		case 4 :
			$images_height_score = 60;
			$comments_images_height = __('A few number of images without height and width attributes were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 5 :
			$images_height_score = 50;
			$comments_images_height = __('A few number of images without height and width attributes were found. Try to fix it to improve your SEO score.', 'tsc');
			break;
		case 6 :
			$images_height_score = 40;
			$comments_images_height = __('You should include attribute height and width attributes for all your images to get a best SEO score.', 'tsc');
			break;
		case 7 :
			$images_height_score = 30;
			$comments_images_height = __('You should include attribute height and width attributes for all your images to get a best SEO score.', 'tsc');
			break;
		default :
			$images_height_score = 0;
			$comments_images_height = __('You must include attribute height and width for all your images. You can rank better if you do.', 'tsc');
			break;
	}
	$errors_images_height = array('url' => $url, 'id_error' => 16, 'num_elements' => $result['images_without_height'], 'comments' => $comments_images_height, 'score' => $images_height_score);
	$errors_url[] = $errors_images_height;
	$tscUrlErrorsModel->insert_url_error($errors_images_height);
		
	// CSS for print
	$result['css_for_print'] = jja_tsc_css_for_print($data);
	if($result['css_for_print'] > 0) $css_print_score = 100;
	else $css_print_score = 0;
	
	$errors_css_print = array('url' => $url, 'id_error' => 17, 'num_elements' => $result['css_for_print'], 'comments' => '', 'score' => $css_print_score);
	$errors_url[] = $errors_css_print;
	$tscUrlErrorsModel->insert_url_error($errors_css_print);
	
	return $errors_url;
}

function jja_tsc_get_url_score_by_errors($data) {
	$final_score = 0;
	foreach($data as $error) {
		$coef = (float) jja_tsc_get_coef($error['id_error']);
		$final_score += ($coef * (int) $error['score']);
	}
	return $final_score;
}

function jja_tsc_set_coeficients() {
	$errorsModel = jja_tsc_errors_model::get_instance();
	$errors = $errorsModel->get_all_errors();
	
	foreach($errors as $error) {
		if(false === ($coef = get_transient('tsc_coef_error_id_'.$error->id))) {
			set_transient('tsc_coef_error_id_'.$error->id, (float) $error->coef, 60 * 60 * 24);
		}
	}
}

function jja_tsc_get_coef($error) {
	if(false === ($coef = get_transient('tsc_coef_error_id_'.$error))) {
		$errorsModel = jja_tsc_errors_model::get_instance();
		$current_error = $errorsModel->get_error_by_id($error);
		set_transient('tsc_coef_error_id_'.$current_error->id, (float) $current_error->coef, 60 * 60 * 24);
		$coef = (float) $current_error->coef;
	}
	return $coef;
}

function jja_tsc_recalc_global_score() {
	$tscUrlsParsed = jja_tsc_urls_parsed_model::get_instance();
	$score = $tscUrlsParsed->get_average_score();
	update_option('jja_tsc_summary_report', array('num_urls' => (int) $score->num_urls, 'score' => (int) $score->score, 'last_time_checked' => $score->timestamp));
}

function jja_tsc_recalc_url_score($url, $id_post = null) {
	$tscUrlsParsedModel = jja_tsc_urls_parsed_model::get_instance();
	$currentScore = $tscUrlsParsedModel->get_by_url($url);
	$old_score = (!empty($currentScore)) ? (float) $currentScore->score : 0;
	
	$data = jja_tsc_get_seo_score_by_url($url);
	$current_score = jja_tsc_get_url_score_by_errors($data);
	
	$positive = ($current_score >= $old_score) ? true : false;
	$delta = abs($current_score - $old_score);
	$improvement = ($positive) ? $delta : ($delta * (-1));

	$tscUrlsParsedModel->delete_score_by_url($url);
	$score = array('url' => $url, 'score' => $current_score, 'improvement' => $improvement);
	if($id_post != null) {
		$current_post = get_post($id_post);
		$score['post_name'] = $current_post->post_name;
	}
	$tscUrlsParsedModel->add_score_by_url($score);
}

function jja_tsc_notice_save($message, $var_name = '', $var2_name = '') { ?>
    <div class="updated">
		<p><?php
			if(!empty($var_name)) {
				if(!empty($var2_name)) {
					printf($message, $var_name, $var2_name);
				} else {
					printf($message, $var_name);
				}
			} else echo $message; ?>
		</p>
    </div><?php
}

function jja_tsc_notice_error($message, $var_name = '', $var2_name = '') { ?>
    <div class="error">
		<p><?php
			if(!empty($var_name)) {
				if(!empty($var2_name)) {
					printf($message, $var_name, $var2_name);
				} else {
					printf($message, $var_name);
				}
			} else echo $message; ?>
		</p>
    </div><?php
}

function jja_tsc_color_code($score, $num_colors = 4) {
	$color_class = 'red';
	
	if($num_colors == 4) {
		if($score <= 25) $color_class = 'red';
		elseif($score <= 50) $color_class = 'orange';
		elseif($score <= 75) $color_class = 'blue';
		else $color_class = 'green';
	} else {
		if($score >= 0) $color_class = 'green';
		else $color_class = 'red';
	}
	
	return $color_class;
}

function jja_tsc_get_score_callback() {
	$url_to_check = (isset($_POST['url']) && !empty($_POST['url'])) ? $_POST['url'] : '';
	if(!empty($url_to_check)) {
		$tscUrlErrorsModel = jja_tsc_urls_errors_model::get_instance();
		$errors = $tscUrlErrorsModel->get_front_errors_by_url($url_to_check);
		if(!empty($errors)) {
			echo json_encode($errors);
		} else {
			return false;
		}
	} else {
		return false;
	}
	die();
}
?>