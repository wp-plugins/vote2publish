<?php
/*
Plugin Name: Vote2Publish
Plugin URI: http://pirex.com.br/wordpress-plugins/one-click-republish
Description: Wordpress MU Plugin: Adds a box in every post of every blog in the community. The post with a certains number of votes is republished into the "main blog"
Author: Leo Germani
Stable tag: 1.0
Author URI: http://pirex.com.br/wordpress-plugins

    Vote 2 Republish is released under the GNU General Public License (GPL)
    http://www.gnu.org/licenses/gpl.txt
	
    
*/

	
		
load_plugin_textdomain('v2r', 'wp-content/mu-plugins/vote2publish');


function v2r_xajax(){
	global $xajax;
	$xajax->registerFunction("vote2publish_vote");
}

function v2r_add_menu(){
	global $current_blog;
	if (function_exists("add_submenu_page")) add_submenu_page("wpmu-admin.php",__('Vote to Republish Options','v2r'), __('Vote to Republish','v2r'), 8, basename(__FILE__), 'vote_admin_page');
	$options = 	get_site_option("vote2publish");
	
	if($current_blog->blog_id!=$options["mainBlog"] && function_exists("add_theme_page")) add_theme_page(__('Vote box layout','v2r'), __('Vote box layout','v2r'), 8, basename(__FILE__), 'vote_box_layout_page');

}

function vote_box_layout_page(){
	
	$optionName="vote2publish_box";
	if(isset($_POST["submit"])){		
		$hide = $_POST["hide"] == 1 ? 1 : 0;
		$newOpt["hide"] = $hide;
		$newOpt["bg"] = $_POST["bg"];
		$newOpt["align"] = $_POST["align"];
		update_option($optionName,$newOpt);
		
	}
	
	if (!get_option($optionName)){
		#load defaults
		$options["hide"] = 0;
		$options["bg"] = 1;
		$options["align"] = "left";
		update_option($optionName,$options);
		
	}else{
		$options = 	get_option($optionName);
	}
	
	?>
	
	<div class="wrap">
	
	<h2><?php _e("Vote box layout"); ?></h2>
	
	<form name="vote2publish" method="post">
	<BR><BR>
	<input type="checkbox" style="width:30px; height: 30px;" value="1" name="hide" <?php if($options["hide"]) echo "checked"; ?>>
	<b><?php _e("Dont show this box!",'v2r'); ?></b>
	<BR><BR>
	<b><?php _e("Choose the box background",'v2r'); ?></b><BR>
	
	<table>
		<?php 
		$i=1;
		for($i==1;$i<=5;$i++){
		?>
	    <tr>
	        <td>
	        <input type="radio" name="bg" value="<?php echo $i; ?>" <?php if($i==$options["bg"]) echo "checked"; ?>
	        </td>
	        
	        <td>
	        <div style="width:70px; height:80px; background: url(<?php bloginfo('url'); ?>/wp-content/mu-plugins/vote2publish/bg-<?php echo $i; ?>.gif)">&nbsp;</div>
	        </td>
	    </tr>
	    <?php
		}
		?>
	</table>
	<BR>
	<b><?php _e("Box Aligment",'v2r'); ?></b><BR>
	<input type="radio" name="align" value="left" <?php if("left"==$options["align"]) echo "checked"; ?>><?php _e("Left"); ?><BR>
	<input type="radio" name="align" value="right" <?php if("right"==$options["align"]) echo "checked"; ?>><?php _e("Right"); ?><BR>
	
	
	
	<div class="submit">
	<input type="submit" name="submit" value="<?php _e('Update Settings','v2r'); ?> &raquo;">
	</div>
	
	</form>
	
	</div>
	
	<?php	

}

function vote_admin_page(){
	global $wpdb, $current_blog;
	
	if(isset($_POST["submit"])){
		
		$active = $_POST["active"] == 1 ? 1 : 0;
		$newOpt["active"] = $active;
		$newOpt["mainBlog"] = $_POST["mainBlog"];
		$newOpt["numVotes"] = $_POST["numVotes"];
		update_site_option("vote2publish",$newOpt);
		
	}
	
	if (!get_site_option("vote2publish")){
		#load defaults
		$options["active"] = 0;
		$options["mainBlog"] = 1;
		$options["numVotes"] = 10;
		
		$sqlTable = "CREATE TABLE `".$wpdb->base_prefix."vote2publish` (
		`user_id` BIGINT NOT NULL ,
		`post_id` BIGINT NOT NULL ,
		`blog_id` BIGINT NOT NULL ,
		PRIMARY KEY ( `user_id` , `post_id` , `blog_id` )
		) ENGINE = MYISAM ";
		
		mysql_query($sqlTable);
		update_site_option("vote2publish",$options);
		
	}else{
		$options = 	get_site_option("vote2publish");
	}
	?>
	
	<div class="wrap">
	
	<h2><?php _e("Vote2Publish Settings"); ?></h2>
	
	<form name="vote2publish" method="post">
	
	<BR>
	<input type="checkbox" style="width:30px; height: 30px;" value="1" name="active" <?php if($options["active"]) echo "checked"; ?>>
	<?php _e("Activate Plugin",'v2r'); ?>
	<BR><BR>
	<?php _e("Please indicate in wich blog the posts should be republished",'v2r'); ?>
	<BR>
	<select name="mainBlog">
		<?php 
		$blogs = get_site_option( "blog_list" );
		if( is_array( $blogs ) ) {
			#reset( $blogs );
			foreach ( (array) $blogs as $b ) {
					echo "<option value='".$b['blog_id']."'";
					if($b['blog_id']==$options["mainBlog"]) echo " selected";
					echo ">".$b['domain']."</option>";	
			}
			
		}
		?>
	</select>
	<BR><BR>
	<?php _e("How many votes a post have to get to be republished?",'v2r'); ?>
	<BR>
	<select name="numVotes">
		<?php 
		$x=1;
		for ($x==1; $x<301; $x++) {
			echo "<option value='$x'";
			if($x==$options["numVotes"]) echo " selected";
			echo ">$x</option>";	
		}
			
		?>
	</select>
	
	<div class="submit">
	<input type="submit" name="submit" value="<?php _e('Update Settings','v2r'); ?> &raquo;">
	</div>
	
	</form>
	
	</div>
	
	<?php
}


function vote_add_button($content){
	global $current_blog,$wpdb;
	$options = 	get_site_option("vote2publish");
	$options_blog = get_option("vote2publish_box");
		
	#Dont add the button to the main blog, nor if the plugin is inactive, nor if the blog owner wants to hide it, nor if the user is not logged in
	if ($options_blog["hide"] || !$options["active"] || $current_blog->blog_id==$options["mainBlog"] || !get_current_user_id()) return $content;
	
	$post = get_the_ID();
	$tableName = $wpdb->base_prefix . "vote2publish";
	
	$count = $wpdb->get_var("SELECT COUNT(*) from $tableName WHERE blog_id = ".$current_blog->blog_id." AND post_id = $post");
	$voted = $wpdb->get_var("SELECT COUNT(*) from $tableName WHERE blog_id = ".$current_blog->blog_id." AND post_id = $post AND user_id = ".get_current_user_id());
	
	
	$vote_button = "
	<div class='vote2publish_button' id='vote2publish_button_".$current_blog->blog_id."_$post' ";
	
	if (!$voted) $vote_button.= "style='cursor:pointer;' onclick='xajax_vote2publish_vote(".get_current_user_id().",".$current_blog->blog_id.",$post)'";
	
	$vote_button.= ">	
		<span id='vote2publish_count_".$current_blog->blog_id."_$post'>$count</span>
		".__("Vote")."	
		</div>";
	return $vote_button.$content;
}

function vote_add_styles(){
	
	
	$options=get_option("vote2publish_box");
	$margins = $options["align"] == "left" ? "margin: 0px 10px 3px 0px;" : "margin: 0px 0px 3px 10px;";
	
	?>	
	<style>
	
	.vote2publish_button{
	
	float:<?php echo $options["align"]; ?>;
	width:70px;
	height:80px;
	<?php echo $margins; ?>
	background: url(<?php bloginfo('url'); ?>/wp-content/mu-plugins/vote2publish/bg-<?php echo $options["bg"]; ?>.gif) no-repeat;

	color: #FFF;
	font-size: 12px;
	text-align: center;
	
	
	font-weight:bold;
	text-decoration: none;
	}	
	.vote2publish_button span{

		font-size: 24px;
		display:block;	
		margin: 15px 0px 15px 0px;
	}

	</style>
	<?php	
}

function vote2publish_vote($user,$blog,$post){
	global $wpdb;
	$tableName = $wpdb->base_prefix . "vote2publish";
	
	$voted = $wpdb->get_var("SELECT COUNT(*) from $tableName WHERE blog_id = ".$blog." AND post_id = $post AND user_id = $user");
	$objResponse = new xajaxResponse();
	
	if(!$voted){
		
		mysql_query("INSERT INTO $tableName(user_id,blog_id,post_id) VALUES($user, $blog, $post)");
		$count = $wpdb->get_var("SELECT COUNT(*) from $tableName WHERE blog_id = ".$blog." AND post_id = $post");
		
		$options = 	get_site_option("vote2publish");
		
		if($options["numVotes"]==$count){
			
			
			$the_post = get_blog_post($blog, $post);
			$orig_link = get_blog_permalink($blog, $post);
			$orig_blog = "<a href='$orig_link'>".get_option("blogname")."</a>";
			
			$orig_time = apply_filters('get_the_time', $the_post->post_date, get_option('time_format'), false);
			
			
			$orig_note = __("Post originally published in %s on %s",'v2r');
			
			
			$content = "<span class='postmetadata'>".sprintf($orig_note, $orig_blog, $orig_time)."</span><BR><BR>".$the_post->post_content;
			$result = array('post_status' => 'publish', 'post_type' => 'post', 'post_author' => $the_post->post_author,
			'post_content' => $content, 'post_title'=>$the_post->post_title);
			
			switch_to_blog($options["mainBlog"]);
			$POSTID = wp_insert_post($result);
			
		
		}		
		$objResponse->addAssign("vote2publish_count_".$blog."_$post","innerHTML", $count);
		#$objResponse->addRemoveHandler("vote2publish_button_".$blog."_$post","click", "xajax_vote2publish_vote");
		$objResponse->addAssign("vote2publish_button_".$blog."_$post","style.cursor", "auto");
		
	}else{
			$objResponse->addAlert(__("You can vote only once in each post",'v2r'));
	}
	return $objResponse;
	
}

add_action('init','v2r_xajax');
add_action('wp_head','vote_add_styles');
add_action('admin_menu','v2r_add_menu');
add_filter('the_content','vote_add_button');

?>
