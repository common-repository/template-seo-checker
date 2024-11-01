<?php 

function jja_tsc_view_faq() { 
	global $non_tsc_post_types;
	$post_types = get_post_types();
	?>
	<div class="wrap">
		<h2><?php _e('FAQ', 'tsc'); ?> | <?php _e('Template SEO Checker', 'tsc');?></h2>
		<ul class="subsubsub">
			<li>
				<a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker"><?php _e('Summary', 'tsc');?></a> |
			</li><?php 
			foreach($post_types as $key => $post_type) :
				if(!in_array($post_type, $non_tsc_post_types)) :
					$obj = get_post_type_object($post_type);  ?>
					<li><a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&check_posttype=<?php echo $post_type; ?>" <?php if(isset($_GET['check_posttype']) && $_GET['check_posttype'] == $post_type) { echo 'class="current"'; } ?>><?php echo $obj->labels->name; ?></a> <span class="count">(<?php echo wp_count_posts($post_type)->publish;?>)</span></a> |</li><?php
				endif;
			endforeach; ?>
		</ul>
		<form id="comments-form" action="" method="get">
			<p class="search-box"><strong><a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&viewfaq=1" class="current" title="<?php _e('FAQ: Frequent Asked Questions', 'tsc'); ?>"><?php _e('FAQ: Frequent Asked Questions', 'tsc'); ?></a></strong></p>
			<br class="clear"><br />
		</form>
		
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder">
				<div class="postbox-container" style="width:75%">
					<table class="widefat" cellspacing="0">
						<thead>
							<tr>
								<th scope="col" colspan="4" class="important">
									<span><strong><?php _e('Frequent Asked Questions', 'tsc'); ?></strong>: <?php _e('Template SEO Checker', 'tsc');?> - <small><?php _e('Is your Wordpress Template good enough for SEO?', 'tsc');?></small></span> 
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="4">
									<h3><?php _e('Why is important that my wordpress template is SEO ready?', 'tsc');?></h3>
									<p><?php _e('If you have been installed this plugin, it is possible that you already know the answer. There are two big areas in the <abbr title="Search Engine Optimization">SEO</abbr> world, the <abbr title="Search Engine Optimization">SEO</abbr> Off page (link building, social media, domain authority...) and <abbr title="Search Engine Optimization">SEO</abbr> On page (website faster, optimized, HTML5 ready, with rich snippets, etc). <br /><br />This plugin, tries to help you to optimize your <abbr title="Search Engine Optimization">SEO</abbr> On page by telling you which things you could change in order to improve your ranking at <abbr title="Search Engine Result Pages">SERP</abbr>. Because you should never forget that if your website ranks better than other, more visits you will have and more money will you earn. (because all this is about the money, isn\'t it?)', 'tsc'); ?></p>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<h3><?php _e('Can you explain me in more deep every SEO factor?', 'tsc');?></h3>
									<p><?php _e('Sure, let\'s see...', 'tsc'); ?></p>
									<ol>
										<li><?php _e('The most important factor that plugin check is <strong>meta name="robots"</strong>. If your theme has it enabled and current value is <strong>"noindex"</strong> your URL score will be <strong>0/100</strong>, because you are telling Google\'s robot and robots from other search engines that you don\'t want that your url will be indexed on Search Engines, so... your theme is not <abbr title="Search Engine Optimization">SEO</abbr> friendly.', 'tsc'); ?></li>
										<li><?php _e('Next factor is determine if your theme uses <strong>inline styles</strong>. Why you should avoid inline styles? First because <strong>it\'s a bad practice</strong> speaking in terms of web development (you should use external <abbr title="Cascading Style Sheets">CSS</abbr>). Second because your code will be dirty and the final size of current URL will be higher, and this affects <abbr title="Search Engine Optimization">SEO</abbr> in terms of <abbr title="Web Performance Optimization">WPO</abbr>. Sites who load faster than other will be rank better in <abbr title="Search Engine Result Pages">SERP</abbr> (<strong>page speed its an important factor on Google search engine algorithm</strong>).', 'tsc');?></li>
										<li><?php _e('Be <strong>responsive ready</strong> is next factor I will check in your themes. A responsive site is better than others no responsive or others who serves same content in different URL in terms of <abbr title="Search Engine Optimization">SEO</abbr>. In fact, if you use a different domain or subdomain for mobile users like mobile.domain.com or something like that you probably will be penalized by Google Panda update, so be carefully.', 'tsc')?></li>
										<li><?php _e('<strong>Headings evalutation</strong>. Do you know that exist <abbr title="HyperText Markup Language">HTML</abbr> tags like &lt;h1&gt;, &lt;h2&gt;, &lt;h3&gt;, &lt;h4&gt;, &lt;h5&gt; and &lt;h6&gt;? And these tags are used to tell search engines what is your page talking about? You should use them with wisdom. Every page should have a different &lt;h1&gt;. Heading tags are used to define the headings in your page.', 'tsc'); ?></li>
										<li><?php _e('The <strong>size in Kb of images used in your theme is important</strong> in terms of <abbr title="Web Performance Optimization">WPO</abbr> (page speed). If your site includes a lot of pics you should try to optimize those pics to do your page load faster.', 'tsc'); ?></li>
										<li><?php _e('If your theme uses a lot of <abbr title="Cascading Style Sheets">CSS</abbr> files you should try to unify all in just one or two external <abbr title="Cascading Style Sheets">CSS</abbr>. This is important because a <strong>fewer number of <abbr title="Cascading Style Sheets">CSS</abbr> can be loaded with fewer server requests</strong>. This will help to make your site load faster.', 'tsc');?></li>
										<li><?php _e('Same as happen with number of <abbr title="Cascading Style Sheets">CSS</abbr> applies to <strong>number of JS</strong> that your theme is using. Try to unify all of them in just on file and include them at the bottom of the <abbr title="HyperText Markup Language">HTML</abbr> code, before &lt;/body&gt; tag.', 'tsc');?></li>
										<li><?php _e('All your URLs should have <strong>a different title tag</strong>. &lt;title&gt; tag is defined at head section of your  <abbr title="HyperText Markup Language">HTML</abbr> code and it\'s important because besides telling search engines what is talking about your page, this title is displayed as snippet in <abbr title="Search Engine Result Pages">SERP</abbr>.', 'tsc');?></li>
										<li><?php _e('Your template should also have a <strong>description</strong> meta tag. This description is displayed in same snippet like title. And its important because tells users what they will find if they click on your result.', 'tsc');?></li>
										<li><?php _e('I talked before about the importance of reduce your image files size, and now its time to <strong>reduce the size in Kb of your <abbr title="Cascading Style Sheets">css</abbr> files</strong>. You should only include necessary styles for your current theme if you want to be faster than your competitors.', 'tsc');?></li>
										<li><?php _e('The same applies to your <strong>javascript files size</strong>. You should reduce it as far as you can to <strong>improve your page load speed</strong>.', 'tsc');?></li>
										<li><?php _e('Another important point that you may take a look is if <strong>all your links have a comprehensive and detailed title attribute</strong>. This attribute indicates to google bot and users what they will find if they click this link. It would be nice if you take the advantage to put your main keywords there.', 'tsc');?></li>
										<li><?php _e('Same as title attribute for links applies to alt attribute for images. You should never forget put <strong>a comprenhensive and detailed description of current image on alt attribute</strong>.', 'tsc'); ?></li>
										<li><?php _e('<strong>The number of <abbr title="Document Object Model">DOM</abbr></strong> elements is also important to determine your <abbr title="Search Engine Optimization">SEO</abbr> On page. Generally a lower number indicates your template is better (but this depends on how many news, articles or how much content you include on every page).', 'tsc'); ?></li>
										<li><?php _e('Having <strong>a template ready form HTML5</strong> is an advantage. Nowadays the major number of modern browsers supports HTML5, this technology will help you to define your <strong>semantic layout</strong>. Thanks to this semantic, search engines can read better your content,', 'tsc'); ?></li>
										<li><?php _e('In <abbr title="HyperText Markup Language">HTML</abbr> language you can even <strong>specify the width and height of your images using attributes (height and width)</strong>. If you do, your browser will load faster your page because it won\'t need wait to load every image to know how to fit it at browser screen. The user experience (UX) will be improved, the bounce rate reduced and the <abbr title="Search Engine Optimization">SEO</abbr> on page better than ever.', 'tsc');?></li>
										<li><?php _e('Finally it will be nice if your template will include a <strong><abbr title="Cascading Style Sheets">CSS</abbr> version for printing</strong>.', 'tcs');?></li>
									</ol>
									<h3><?php _e('Will you include more SEO factors in future release?', 'tsc');?> - <?php _e('I want to check my tags, categories, author and other pages', 'tsc');?></h3>
									<p><?php _e('Actually <strong>Template SEO Checker</strong> checks about 17 factors to determine if your theme is <abbr title="Search Engine Optimization">SEO</abbr> friendly or not. In future releases more factors will be added and most kind of wordpress pages like (author, category, tag, error404, search, etc.) can be checked.<br />Depending on the success of this plugin (people using it), comments and donations received <strong>Template <abbr title="Search Engine Optimization">SEO</abbr> will be improved</strong>.', 'tsc'); ?></p>
									<h3><?php _e('How can I thank you all your efforts?', 'tsc');?></h3>
									<p><?php _e('You can donate between $1,00 to $10,00 if you enjoyed this plugin. More options will be included on future releases.')?></p>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<h3><?php _e('How you determine final score?', 'tsc');?></h3>
									<p><?php _e('Based on my experience of <abbr title="Search Engine Optimization">SEO</abbr> manager I developed an algorithm who scraps your theme and check every factor defined in the plugin to know if your template is <abbr title="Search Engine Optimization">SEO</abbr> friendly. Then, foreach factor your theme gets an score and the sum of all of them determines the current URL score.', 'tsc'); ?></p>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<h3><?php _e('What should I do if my wordpress template had a lower score?', 'tsc');?></h3>
									<p><?php _e('If you don\'t care about <abbr title="Search Engine Optimization">SEO</abbr>, don\'t do anything. If you care about <abbr title="Search Engine Optimization">SEO</abbr> and you don\'t mind about your design you can try another wordpress theme. If you really like your current theme, you can try to fix the errors manually. And if you don\'t have the knowledge to fix the errors manually <strong>you can try to hire me</strong>.', 'tsc'); ?></p>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<h3><?php _e('Can I hire you to check and fix my wordpress site to be ready for <abbr title="Search Engine Optimization">SEO</abbr>?', 'tsc');?></h3>
									<p><?php _e('<strong>Of course!</strong> You can contact me anytime writing me or following on <a href="https://plus.google.com/+JonatanJumbert" title="View Jonatan Jumbert\'s Google+ profile">Google+</a>, <a href="http://twitter.com/jonatanjumbert" title="View Jonatan Jumbert\'s Twitter profile">Twitter</a>, <a href="http://www.linkedin.com/pub/jonatan-jumbert-avil%C3%A9s/14/540/443" title="View Jonatan Jumbert\'s Linkedin profile">Linkedin</a>, <a href="https://www.facebook.com/jonatan.jumbert" title="View Jonatan Jumbert\'s Facebook profile">Facebook</a> or in <a href="http://jonatanjumbert.com/blog/?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins" title="View Jonatan Jumbert\'s personal blog">my personal site</a>. Tell me about you and your site and let\'s see if we come to an agreement.', 'tsc'); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php jja_tsc_print_widget(); ?>
			</div>
		</div>
	</div><?php
}

/**
 * Renders Template SEO Checker Main Page
 */
function jja_template_seo_checker_config_page() {
	if(isset($_GET['viewfaq'])) {
		jja_tsc_view_faq();
	} else {
		wp_enqueue_style('tsc.css', TSC_MEDIA_URI.'/css/tsc_css.css', false, TSC_VERSION_NUM, false);
		wp_enqueue_style('bootstrap.min.css', TSC_MEDIA_URI.'/css/bootstrap.min.css', false, TSC_VERSION_NUM, false);
		wp_register_script('modal.js', TSC_MEDIA_URI.'/js/modal.js', array(), TSC_VERSION_NUM, false);
		wp_enqueue_script('modal.js');
		
		require_once ABSPATH . 'wp-admin/includes/dashboard.php';
		global $non_tsc_post_types;
		$post_types = get_post_types();
		 
		if(isset($_GET['recalc']) && $_GET['recalc'] == 'all') {
			jja_tsc_recalc_global_score();
			add_action('admin_notices', 'jja_tsc_notice_save', 10, 3);
			do_action('admin_notices', __("<strong>Template SEO Checker</strong> have been updated your theme score.", 'tsc'), '', '');
		} else if(isset($_GET['recalc']) && $_GET['recalc'] == 'homepage') {
			jja_tsc_recalc_url_score(get_bloginfo('wpurl'));
			add_action('admin_notices', 'jja_tsc_notice_save', 10, 3);
			do_action('admin_notices', __("<strong>Template SEO Checker</strong> have been updated your homepage SEO score.", 'tsc'), '', '');
		} else if(isset($_GET['check_posttype']) && isset($_GET['recalc']) && !empty($_GET['check_posttype']) && !empty($_GET['recalc'])) {
			foreach($post_types as $key => $post_type) {
				if($_GET['check_posttype'] == $post_type && !in_array($post_type, $non_tsc_post_types)) {
					$id_post = (int) $_GET['recalc'];
					$url_actualizar = get_permalink($id_post);
					jja_tsc_recalc_url_score($url_actualizar, $id_post);
					add_action('admin_notices', 'jja_tsc_notice_save', 10, 3);
					do_action('admin_notices', __("<strong>Template SEO Checker</strong> have been updated your current URL SEO score.", 'tsc'), '', '');
				}
			}
		}?>
		<div class="wrap">
			<h2><?php _e('Template SEO Checker', 'tsc');?></h2>
			<ul class="subsubsub">
				<li>
					<a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker" <?php if(!isset($_GET['check_posttype'])) { echo 'class="current"'; } ?>><?php _e('Summary', 'tsc');?></a> |
				</li><?php 
				foreach($post_types as $key => $post_type) :
					if(!in_array($post_type, $non_tsc_post_types)) :
						$obj = get_post_type_object($post_type);  ?>
						<li><a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&check_posttype=<?php echo $post_type; ?>" <?php if(isset($_GET['check_posttype']) && $_GET['check_posttype'] == $post_type) { echo 'class="current"'; } ?>><?php echo $obj->labels->name; ?></a> <span class="count">(<?php echo wp_count_posts($post_type)->publish;?>)</span></a> |</li><?php
					endif;
				endforeach; ?>
			</ul>
			<form id="comments-form" action="" method="get">
				<p class="search-box"><a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&viewfaq=1" title="<?php _e('FAQ: Frequent Asked Questions', 'tsc'); ?>"><?php _e('FAQ: Frequent Asked Questions', 'tsc'); ?></a></p>
				<br class="clear"><br />
			</form>
			
			<div id="dashboard-widgets-wrap">
				<div id="dashboard-widgets" class="metabox-holder">
					<div class="postbox-container" style="width:75%"><?php 
						if(isset($_GET['check_posttype']) && in_array($_GET['check_posttype'], $post_types)) {
							jja_tsc_check_posttypes($_GET['check_posttype']);	
						} else {
							jja_tsc_summary();
						} ?>
					</div>
					<?php jja_tsc_print_widget(); ?>
				</div>
			</div>
		</div>
		<div id="modal-advise" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 class="modal-title" id="myLargeModalLabel"><?php _e('Template SEO Checker', 'tsc');?></h4>
					</div>
					<div class="modal-body">
						<?php _e('This action can take several seconds. Are you sure you want to continue?', 'tsc')?>
						<input type="hidden" id="url-modal-action" value="" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'tsc'); ?></button>
						<button type="button" id="modal-confirm" class="btn btn-primary"><?php _e('Continue', 'tsc'); ?></button>
	        		</div>
				</div>
			</div>
		</div>
		<script>
			jQuery('a.advice').click(function(e) {
				e.preventDefault();
				jQuery('#url-modal-action').val('');
				var url = jQuery(this).attr('href');
				jQuery('#url-modal-action').val(url);
				jQuery('#modal-advise').modal();
				return false;
			});
			jQuery('#modal-confirm').click(function(e) {
				e.preventDefault();
				jQuery('#modal-advise').modal('toggle');
				window.location.href = jQuery('#url-modal-action').val();
				return false;
			});
		</script><?php
	}
}

function jja_tsc_print_widget() { 
	$locale = get_locale(); ?>
	<div id="postbox-container-1" class="postbox-container" style="width:25%">
		<div id="normal-sortables" class="meta-box-sortables ui-sortable">
			<div id="dashboard_quick_press" class="postbox ">
				<h3 style="cursor:auto;"><?php _e('About the author', 'tsc'); ?></h3>
				<div class="inside">
					<form name="post" action="https://www.paypal.com/cgi-bin/webscr" method="post" id="quick-press" class="initial-form hide-if-no-js">
						<div id="author_desc">
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/plugin-author.jpg" class="foto-author"/>
							<p><?php _e('My name is <strong>Jonatan Jumbert</strong>, I am SEO professional and a web developer who enjoys to take on new projects. If do you want know more about me you can check out my <a href="https://plus.google.com/u/0/+JonatanJumbert/posts" titlte="Author Google+ profile" target="_blank">Google+ profile</a> or me <a href="http://jonatanjumbert.com?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins" target="_blank" title="Author site">personal site</a>.', 'tsc'); ?> <strong><?php _e('Do you wanna hire me?', 'tsc');?></strong></p>
						</div>
						<div class="sidebar-name">
							<h3><?php _e('Make a donation', 'tsc'); ?></h3>
						</div>
						<div id="sidebar-2" style="min-height: 50px; ">
							<div class="sidebar-description">
								<p class="description"><?php _e("Do you like this plugin? Why don't contribute with a little donation?", 'tsc'); ?></p>
								<p class="description"><?php _e('Make author happy and pay him something', 'tsc');?>.</p>
								<div class="paypal-donations">
									<input type="hidden" name="cmd" value="_donations" />
									<input type="hidden" name="business" value="jonatan.jumbert@gmail.com" />
									<input type="hidden" name="item_name" value="Template SEO Checker" />
									<input type="hidden" name="return" value="<?php echo get_bloginfo('wpurl'); ?>/admin.php?page=template-seo-checker&donation=thanks" />
									<?php if($locale == 'en_US') : ?>
										<select name="amount">
											<option value="1"><?php _e('Pay a coffee - &dollar;1,00 USD', 'tsc'); ?></option>
											<option value="2"><?php _e('Pay a beer - &dollar;2,00 USD', 'tsc'); ?></option>
											<option value="3"><?php _e('Pay a snack - &dollar;3,00 USD', 'tsc'); ?></option>
											<option value="5"><?php _e('Pay a drink - &dollar;5,00 USD', 'tsc'); ?></option>
											<option value="10"><?php _e('Pay the cinema - &dollar;10,00 USD', 'tsc'); ?></option>
										</select><br />
										<input type="hidden" name="currency_code" value="USD" />
									<?php else : ?>
										<select name="amount">
											<option value="1"><?php _e('Pay a coffee - 1&euro;', 'tsc'); ?></option>
											<option value="2"><?php _e('Pay a beer - 2&euro;', 'tsc'); ?></option>
											<option value="3"><?php _e('Pay a snack - 3&euro;', 'tsc'); ?></option>
											<option value="5"><?php _e('Pay a drink - 5&euro;', 'tsc'); ?></option>
											<option value="10"><?php _e('Pay the cinema - 10&euro;', 'tsc'); ?></option>
										</select><br />
										<input type="hidden" name="currency_code" value="EUR" />
									<?php endif; ?>
									<input type="image" src="<?php echo TSC_MEDIA_URI; ?>/img/<?php _e('donate_en.gif', 'tsc'); ?>" name="submit" alt="<?php _e('PayPal - The safer, easier way to pay online.', 'tsc'); ?>" width="92" height="26" style="width: auto;"/>
								</div>
								<h3><?php _e('You can follow me on', 'tsc'); ?></h3>
								<p class="description">
					                <a href="http://jonatanjumbert.com/blog/?utm_source=Wordpress&utm_medium=Plugin&utm_term=Template%20SEO%20Checker&utm_campaign=Wordpress%20plugins" title="<?php _e('View Jonatan Jumbert\'s personal blog', 'tsc'); ?>">
					                	<img src="<?php echo TSC_MEDIA_URI; ?>/img/blog.png" alt="<?php _e('Follow me on my blog', 'tsc'); ?>" height="64" width="64">
					                </a>
					                <a href="https://plus.google.com/+JonatanJumbert" title="<?php _e('View Jonatan Jumbert\'s Google+ profile', 'tsc'); ?>">
					                	<img src="<?php echo TSC_MEDIA_URI; ?>/img/googleplus.png" alt="<?php _e('Follow me on Google+', 'tsc'); ?>" height="64" width="64">
					                </a>
					                <a href="http://www.linkedin.com/pub/jonatan-jumbert-avil%C3%A9s/14/540/443" title="<?php _e('View Jonatan Jumbert\'s Linkedin profile', 'tsc'); ?>">
					                	<img src="<?php echo TSC_MEDIA_URI; ?>/img/linkedin.png" alt="<?php _e('Follow me on Linkedin', 'tsc'); ?>" height="64" width="64">
					                </a>
					                <a href="http://twitter.com/jonatanjumbert" title="<?php _e('View Jonatan Jumbert\'s Twitter profile', 'tsc'); ?>">
					                	<img src="<?php echo TSC_MEDIA_URI; ?>/img/twitter.png" alt="<?php _e('Follow me on Twitter', 'tsc'); ?>" height="64" width="64">
					                </a>
					                <a href="https://www.facebook.com/jonatan.jumbert" title="<?php _e('View Jonatan Jumbert\'s Facebook profile', 'tsc'); ?>">
					                	<img src="<?php echo TSC_MEDIA_URI; ?>/img/facebook.png" alt="<?php _e('Follow me on Facebook', 'tsc'); ?>" height="64" width="64">
					                </a>
				               	</p>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div><?php
}

function jja_tsc_summary() {
	$summary_report = get_option('jja_tsc_summary_report', false); 
	
	$locale = get_locale(); 
	$urlsParsedModel = jja_tsc_urls_parsed_model::get_instance();
	$urlsErrorsModel = jja_tsc_urls_errors_model::get_instance();
	$errorsModel = jja_tsc_errors_model::get_instance();
	
	$home_seo_score = $urlsParsedModel->get_by_url(get_bloginfo('wpurl'));
	$detailedHomeErrors = $urlsErrorsModel->get_errors_by_url(get_bloginfo('wpurl')); ?>
	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" colspan="4" class="important">
					<span><strong><?php _e('Summary', 'tsc'); ?></strong>: <?php _e('Template SEO Checker', 'tsc');?> - <small><?php _e('Is your Wordpress Template good enough for SEO?', 'tsc');?></small></span> 
				</th>
			</tr>
		</thead>
		<tbody>
			<?php if(empty($summary_report)) : ?>
				<td class="author column-author">
					<img src="<?php echo TSC_MEDIA_URI; ?>/img/alert.png" alt="<?php _e('No urls have been checked yet', 'tsc'); ?>" />
					<strong><?php _e('No urls have been checked yet', 'tsc'); ?></strong>
				</td>
				<td width="25%">&nbsp;</td>
				<td class="center">
					<p><?php _e('Template SEO score');?>:</p>
					<span class="summary_score">???/100</span>
				</td>
				<td width="10%" class="center">
					<br />
					<a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&recalc=all" class="advice" title="<?php _e('Recalc SEO score for current wordpress template', 'tsc'); ?>">
						<img src="<?php echo TSC_MEDIA_URI; ?>/img/recalc.png" height="24" width="24" alt="<?php _e('Recalc SEO score for current wordpress template', 'tsc'); ?>" />
					</a>
				</td>
			<?php else :
				$score = (int) $summary_report['score'];
				$locale = get_locale();
				$summary_date = ($locale == 'en_US' || 'en_EN') ? date('Y/m/d - h:m:s', strtotime($summary_report['last_time_checked'])) : date('d/m/Y - h:m:s', strtotime($summary_report['last_time_checked'])); ?>
				<tr>
					<td class="author column-author"><?php 
						if($score <= 25) : $color_class = 'red'; ?>
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/trash.png" alt="<?php _e('No SEO friendly', 'tsc'); ?>" />
							<strong><?php _e('Your template looks like trash speaking in SEO terms.', 'tsc'); ?></strong>
							<br />
							<p><?php _e('I hope you did not pay for this theme. Because it\'s not SEO friendly and a lot of things can be done better.', 'tsc');?></p><?php
						elseif($score < 50) : $color_class = 'orange'; ?>
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/cross.png" alt="<?php _e('It could be better', 'tsc'); ?>"/>
							<strong><?php _e('Your template it\'s not all SEO frendly as it could be.', 'tsc'); ?></strong> 
							<br /><p><?php _e('I hope you did not pay for this theme. Because it\'s not SEO friendly and a lot of things can be done better.', 'tsc');?></p><?php
						elseif($score < 75) : $color_class = 'blue'; ?>
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/tick.png" alt="<?php _e('Nice! Your template is SEO friendly', 'tsc'); ?>"/>
							<strong><?php _e('Nice! Your template is SEO friendly.', 'tsc'); ?></strong>
							<br /><?php _e('Well done! Your template is SEO friendly. But, you should take care about some points because you can improve even more you SEO onsite.', 'tsc');
						else : $color_class = 'green'; ?>
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/heart.png" alt="<?php _e('Awesome! Your template is ready for SEO', 'tsc'); ?>"/>
							<strong><?php _e('Absolutely awesome! Your SEO onsite looks perfect.', 'tsc'); ?></strong>
							<br /><?php _e('Absolutely awesome! Seems like your template is ready for SEO. Your SEO onsite looks perfect. Don\'t forget check all urls as you can in order to have accurate results.', 'tsc');
						endif; ?> 
					</td>
					<td width="25%">
						<div class="submitted-on"><?php _e('Last time checked on', 'tsc'); ?> <a href=""><?php echo $summary_date; ?></a></div>
						<p><?php echo sprintf(__('Report based on <strong>%s</strong> urls checked', 'tsc'), $summary_report['num_urls']);?></p>
					</td>
					<td class="center">
						<p><?php _e('Template SEO score');?>:</p>
						<span class="summary_score <?php echo $color_class; ?>"><?php echo $summary_report['score']; ?>/100</span>
					</td>
					<td width="10%" class="center">
						<br />
						<a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&recalc=all" class="advice" title="<?php _e('Recalc SEO score for current wordpress template', 'tsc'); ?>">
							<img src="<?php echo TSC_MEDIA_URI; ?>/img/recalc.png" height="24" width="24" alt="<?php _e('Recalc SEO score for current wordpress template', 'tsc'); ?>" />
						</a>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
	<br />
	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" colspan="5" class="important">
					<span><strong><?php echo ucfirst(__('homepage', 'tsc')); ?></strong>: <?php _e('Template SEO Checker', 'tsc');?> - <small><?php _e('Is your homepage good enough for SEO?', 'tsc');?></small></span>
				</th>
			</tr>
			<tr>
				<th><?php _e('URL', 'tsc');?></th>
				<th><?php _e('Test date', 'tsc');?></th>
				<th class="center"><?php _e('SEO Score', 'tsc');?></th>
				<th class="center"><?php _e('Improvement', 'tsc');?></th>
				<th></th>
			</tr> 
		</thead>
		<tbody>
			<tr> 
				<td class="author column-author">
					<strong><?php echo get_bloginfo('name');?></strong><br />
					<p><a href="<?php echo get_bloginfo('wpurl');?>" target="_blank"><?php echo get_bloginfo('wpurl'); ?></a></p>
				</td>
				<td width="25%">
					<?php if(empty($home_seo_score)) : ?>
						<?php _e('No tested yet', 'tsc'); ?>
					<?php else : ?>
						<?php $home_seo_date = ($locale == 'en_US' || 'en_EN') ? date('Y/m/d - h:m:s', strtotime($home_seo_score->time_checked)) : date('d/m/Y - h:m:s', strtotime($home_seo_score->time_checked)); ?>
						<div class="submitted-on"><?php _e('Last time checked on', 'tsc'); ?> <a href=""><?php echo $home_seo_date; ?></a></div>
					<?php endif; ?>
				</td>
				<td class="center notsummary">
					<?php if(empty($home_seo_score)) : ?>
						<span class="summary_score">???/100</span>
					<?php else : 
						$score = (float) $home_seo_score->score;
						$color_class = jja_tsc_color_code($score, 4); ?>
						<span class="summary_score <?php echo $color_class; ?>"><?php echo $score; ?>/100</span>
					<?php endif; ?>
				</td>
				<td class="center notsummary">
					<?php if(empty($home_seo_score)) : ?>
						???
					<?php else :
						$improvement = (float) $home_seo_score->improvement;
						$color = jja_tsc_color_code($improvement, 1); ?>
							<span class="summary_score <?php echo $color; ?>"><?php echo $improvement; ?></span>
					<?php endif; ?>
				</td>
				<td width="10%" class="center">
					<a href="<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin.php?page=template-seo-checker&recalc=homepage&check_posttype=homepage" class="advice" title="<?php _e('Recalc SEO score for your homepage template', 'tsc'); ?>">
						<img src="<?php echo TSC_MEDIA_URI; ?>/img/recalc.png" height="24" width="24" alt="<?php _e('Recalc SEO score for your homepage template', 'tsc'); ?>" />
					</a>
				</td>
			</tr><?php
			if(!empty($detailedHomeErrors)) : ?>
				<tr>
					<td colspan="5">
						<table>
							<thead>
								<tr>
									<th><strong><?php _e('Factor', 'tsc');?></strong></th>
									<th class="center"><strong><?php _e('SEO Score', 'tsc');?></strong></th>
									<th><strong><?php _e('Comments', 'tsc');?></strong></th>
								</tr> 
							</thead><?php 
							foreach($detailedHomeErrors as $error) : 
								$errorLabel = $errorsModel->get_error_by_id($error->id_error); ?>
								<tr>
									<td><?php _e($errorLabel->error, 'tsc'); ?></td>
									<td class="center"><span class="<?php echo jja_tsc_color_code($error->score, 4);?>"><?php echo $error->score; ?>/100</span></td>
									<td>
										<?php if($error->id_error == 4) : ?>
											<?php if(empty($error->comments)) : ?>
												&nbsp;
											<?php else : 
												$errores = explode('||', $error->comments);
												$headings = explode('--', $errores[0]);
												endif; ?>
												<ul>
													<?php if(isset($errores[1]) && !empty($errores[1])) : ?>
														<li><?php echo $errores[1]; ?></li>
													<?php endif; ?>
													<?php foreach($headings as $heading) : ?>
														<li><?php echo $heading; ?></li>
													<?php endforeach;?>
												</ul>
										<?php else : ?>
											<?php echo $error->comments; ?>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</td>
				</tr><?php 
			endif; ?>
		</tbody>
	</table><?php
}

function jja_tsc_check_posttypes($post_type) { 
	$postTypeTable = new TemplateSeoChecker_List_Table(20, $post_type);
	$postTypeTable->prepare_items();
	$postTypeTable->display(); ?>
	
	<div id="tscDetails" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 class="modal-title" id="myLargeModalLabel">Large modal</h4>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', 'tsc'); ?></button>
        		</div>
			</div>
		</div>
	</div>
	<script>
		jQuery('.view-summary').click(function(e) {
			e.preventDefault();
			var url = jQuery(this).attr('data-url');
			var current_score = jQuery(this).attr('data-score');
			var modal = jQuery('#tscDetails');
			jQuery('h3', modal).html('<?php _e('SEO Summary for:', 'tsc');?> ' + url+ '<span style="float:right; margin-right:10px;">'+current_score+'/100</span>').attr('style', 'font-weight:bolder');

			var data = {action: 'jja_tsc_get_score', url: url};
			jQuery.post(ajaxurl, data, function(response) {
				if(response.length > 1) {
					var table = jQuery('<table class="widefat">').clone();
					var thead = jQuery('<thead>').clone();
					var tbody = jQuery('<tbody>').clone();
					thead.html('<tr><tr><th style="width:40%"><strong><?php _e('Factor', 'tsc');?></strong></th><th class="center" style="width:10%"><strong><?php _e('SEO Score', 'tsc');?></strong></th><th style="width:50%"><strong><?php _e('Comments', 'tsc');?></strong></th></tr>');

					jQuery('div.modal-body table', modal).remove();
					table.appendTo(jQuery('div.modal-body', modal));
					thead.appendTo(table);
					tbody.appendTo(table);
					
					var errores = jQuery.parseJSON(response);

					for(var i = 0; i < errores.length; i++) {
						var tr = jQuery('<tr>').clone();
						tr.html('<td>'+errores[i].error+'</td><td class="center">'+errores[i].score+'</td><td>'+errores[i].comments+'</td>');
						tr.appendTo(tbody);
					}
				}
			});
			jQuery('#tscDetails').modal();
		});
	</script><?php
}
?>