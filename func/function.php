<?php
function WARP_showErr($ErrMsg) {
    header('HTTP/1.0 500 Internal Server Error');
	header('Content-Type: text/plain;charset=UTF-8');
    echo $ErrMsg;
    exit;
}
function WARP_init(){
	if($_GET['action'] == 'wpAjaxRecentPosts'){
		$jsonArr=array();
		$_num=$_GET['number'];
		$_offset=$_GET['offset'];
		$_cmtcount=$_GET['cmtcount'];
		$_excerpt=$_GET['excerpt'];
		$_length=$_GET['length'];
		echo WARP_Recent_posts("number=$_num&cmtcount=$_cmtcount&excerpt=$_excerpt&length=$_length&offset=$_offset");
		die();
	}
}
add_action('init', 'WARP_init');

function WARP_Recent_posts($args=''){
	$defargs=array('number' => 8, 'offset' => 0, 'cmtcount' => 0, 'excerpt' => 0, 'length' => 100);
	$args = wp_parse_args($args, $defargs);$output='';$number=$args['number'];$offset=$args['offset'];
	query_posts("showposts=$number&offset=$offset");
	if(have_posts()){
		while (have_posts()) :the_post();
			$output.='<li id="recent-post-'.get_the_ID().'" class="recent-post"><div class="recent-post-title"><a title="'.get_the_title().'" class="recent-post-link" href="'.get_permalink().'">'.get_the_title().'</a>';
			if($args['cmtcount']!=0)$output.='('.get_comments_number().')';
			$output.='</div>';
			if($args['excerpt']!=0)$output.='<div class="recent-post-excerpt">'.WARP_Recent_posts_substr(strip_tags(get_the_content()),(int)$args['length']).'</div>';;
			$output.='</li>';
		endwhile;
		$output.='<li id="recent-post-more" class="recent-post" style="text-align:center"><div><a style="display:block;width:100%;height:100%" href="javascript:;" onclick="WARP.get_recent_posts(\'number='.$args['number'].'&cmtcount='.$args['cmtcount'].'&excerpt='.$args['excerpt'].'&length='.$args['length'].'&offset='.((int)$args['offset']+(int)$args['number']).'\')">'.__('More', 'WP-Ajax-Recent-Posts').'</a></div></li>';
		return $output;
	}else{
		WARP_showErr(__('There is no post.','WP-Ajax-Recent-Posts'));
	}
}
function WP_Ajax_Recent_Posts($args=''){
	echo '<ul id="wp-recent-posts">'.WARP_Recent_posts($args."&offset=0").'</ul>';
}
function WARP_Recent_posts_substr($str,$length){
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $str, $t_str);
		if(count($t_str[0]) > $length) {
			$ellipsis = '...';
			$str = join('', array_slice($t_str[0], 0, $length)) . $ellipsis;
		}
		return $str;
}
add_action('admin_menu', 'WARP_add_options');

function WARP_add_options() {
	add_options_page('Ajax Recent Posts', __("Ajax Recent Posts","WP-Ajax-Recent-Posts"), 8, __FILE__, 'WARP_the_options');
}
function WARP_addScript(){
	$script = '<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/wp-ajax-recent-posts/js/wp-ajax-recent-posts.js"></script>';
	echo $script;
}
if(get_option("WP-Ajax-Recent-files")!='1')add_action ('wp_head', 'WARP_addScript');
else add_action ('wp_footer', 'WARP_addScript');
class WARP_widget extends WP_Widget{
	function WARP_widget(){
		$widget_des = array('classname'=>'WARP_Recent_posts','description'=>__('Show your recent posts on sidebar and provide ajax pagenav function.', 'WP-Ajax-Recent-Posts'));
		$this->WP_Widget(false,__('Ajax Recent Posts', 'WP-Ajax-Recent-Posts'),$widget_des);
	}
	function form($instance){
		$instance = wp_parse_args((array)$instance,array(
		'title'=>__('Recent Posts', 'WP-Ajax-Recent-Posts'),
		'number'=>8,
		'cmtcount'=>false,
		'excerpt'=>false,
		'length'=>100));
		echo '<p><label for="'.$this->get_field_name('title').'">'.__('widget title: ', 'WP-Ajax-Recent-Posts').'<input style="width:200px;" name="'.$this->get_field_name('title').'" type="text" value="'.htmlspecialchars($instance['title']).'" /></label></p>';
		echo '<p><label for="'.$this->get_field_name('number').'">'.__('The number of recent posts', 'WP-Ajax-Recent-Posts').'<input style="width:200px;" name="'.$this->get_field_name('number').'" type="text" value="'.htmlspecialchars($instance['number']).'" /></label></p>';
		echo '<p><input style="" name="'.$this->get_field_name('cmtcount').'" type="checkbox" value="checkbox" ';if($instance['cmtcount'])echo 'checked="checked"';echo '/><label for="'.$this->get_field_name('cmtcount').'">'.__('show post comments count?', 'WP-Ajax-Recent-Posts').'</label></p>';
		echo '<p><input style="" name="'.$this->get_field_name('excerpt').'" type="checkbox" value="checkbox" ';if($instance['excerpt'])echo 'checked="checked"';echo '/><label for="'.$this->get_field_name('excerpt').'">'.__('show post excerpt?', 'WP-Ajax-Recent-Posts').'</label></p>';
		echo '<p><label for="'.$this->get_field_name('length').'">'.__('The length of excerpt', 'WP-Ajax-Recent-Posts').'<input style="width:200px;" name="'.$this->get_field_name('length').'" type="text" value="'.htmlspecialchars($instance['length']).'" /></label></p>';
	}
	function update($new_instance,$old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['number'] = (int)$new_instance['number'];
		$instance['cmtcount'] = (bool)$new_instance['cmtcount'];
		$instance['excerpt'] = (bool)$new_instance['excerpt'];
		$instance['length'] = (int)$new_instance['length'];
		return $instance;
	}
	function widget($args,$instance){
		extract($args);
		$myargs='number='.$instance['number'].'&cmtcount='.((int)$instance['cmtcount']).'&excerpt='.((int)$instance['excerpt']).'&length='.$instance['length'];
		$title = apply_filters('widget_title',empty($instance['title']) ? __('Enjoy Reading', 'WP-Ajax-Recent-Posts') : $instance['title']);
		echo '<li id="recent-post-widget" class="widget"><h3>'.$title.'</h3>';
		WP_Ajax_Recent_Posts($myargs);
		echo '</li>';
	}
}
function WARP_widget_init(){
	register_widget('WARP_widget');
}
add_action('widgets_init','WARP_widget_init');
?>