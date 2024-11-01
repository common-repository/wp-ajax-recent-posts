<?php
/*
Plugin Name: WP-Ajax-Recent-Posts
Plugin URI: http://www.qiqiboy.com/plugins/
Tags: wordpress, ajax, recent, posts
Description: Show your recent posts on sidebar and provide ajax pagenav function.
Version: 1.0.1
Author: QiQiBoY
Author URI: http://www.qiqiboy.com
*/
load_plugin_textdomain('WP-Ajax-Recent-Posts', false, basename(dirname(__FILE__)) . '/lang');
require_once(dirname(__FILE__).'/func/function.php');
function WARP_the_options() {
?>
<div class="wrap">

	<h2><?php _e('WP-Ajax-Recent-Posts Help and Options','WP-Ajax-Recent-Posts');?></h2>
	<b><?php _e('How to use this plug-in?','WP-Ajax-Recent-Posts');?></b><br><br>
	<ol>
		<li><del style="color:#888;"><?php _e('Download the plugin archive and expand it','WP-Ajax-Recent-Posts');?></del> <?php _e('(you\'ve likely already done this).','WP-Ajax-Recent-Posts');?></li>
		<li><del style="color:#888;"><?php _e('Put the \'WP-Ajax-Recent-Posts\' directory into your wp-content/plugins/ directory','WP-Ajax-Recent-Posts');?></del> <?php _e('(you\'ve likely already done this).','WP-Ajax-Recent-Posts');?></li>
		<li><del style="color:#888;"><?php _e('Go to the Plugins page in your WordPress Administration area and click \'Activate\' for WP-Ajax-Recent-Posts','WP-Ajax-Recent-Posts');?></del> <?php _e('(you\'ve likely already done this).','WP-Ajax-Recent-Posts');?></li>
		<li><del style="color:#888;"><?php _e('Go to the WP-Ajax-Recent-Posts Options page (Settings > WP-Ajax-Recent-Posts Option)','WP-Ajax-Recent-Posts');?></del> <?php _e('(you\'ve likely already done this).','WP-Ajax-Recent-Posts');?></li>
		<li><?php _e('1.Go to widgets page to add the widget to your sidebar.','WP-Ajax-Recent-Posts');?><br>
			<?php _e('2.use the function ','WP-Ajax-Recent-Posts');?><code>&lt;?php WP_Ajax_Recent_Posts('number=8&cmtcount=1&excerpt=1&length=100'); ?></code><?php _e(' to custom the posts display position.','WP-Ajax-Recent-Posts');?><br>
			<?php _e('This function accepts four parameters:','WP-Ajax-Recent-Posts');?><br>
			<code>number</code>: <?php _e('how many posts display. Type: Integer, default 8','WP-Ajax-Recent-Posts');?><br>
			<code>cmtcount</code>: <?php _e('display comments count of the post. Type: Boolean, default 0. 1: yes, 0: no','WP-Ajax-Recent-Posts');?><br>
			<code>excerpt</code>: <?php _e('display excerpt of no. Type: Boolean, default 0. 1: yes, 0: no','WP-Ajax-Recent-Posts');?><br>
			<code>length</code>: <?php _e('the length of the excerpt. Type: Integer, default 100','WP-Ajax-Recent-Posts');?><br>
		</li>
	</ol>
	<br>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		
		<h3><?php _e('Some configuration:','WP-Ajax-Recent-Posts');?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('javascript and css files add to', 'WP-Ajax-Recent-Posts'); ?></th>
				<td>
				<select style="width:120px;text-align:center" name="WP-Ajax-Recent-files">
					<option value="0" <?php if(get_option("WP-Ajax-Recent-files")=="0") echo "selected='selected'"; ?>><?php _e('header', 'WP-Ajax-Recent-Posts'); ?></option>
					<option value="1" <?php if(get_option("WP-Ajax-Recent-files")=="1") echo "selected='selected'"; ?>><?php _e('footer', 'WP-Ajax-Recent-Posts'); ?></option>
				</select>
				</td>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="WP-Ajax-Recent-files" />

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes','WP-Ajax-Recent-Posts') ?>" />
		</p>

	</form>
</div>
<?php
}
?>