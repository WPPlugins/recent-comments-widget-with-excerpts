<?php
/*
Plugin Name: Recent comments widget with excerpts
Plugin URI: http://www.tacticaltechnique.com/wordpress/recent-comments-widget-with-excerpts/
Description: Duplicates the built-in recent comments widget to show excerpts instead of post titles
Author: Corey Salzano
Version: 0.111017
Author URI: http://www.tacticaltechnique.com/
*/

//	i have created two recent comments plugins. this one
//	duplicates the default widget and adds functionality
//	the other plugin replaces the default recent comments
//	widget

/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Recent_Comments_Excerpts extends WP_Widget {

	function WP_Widget_Recent_Comments_Excerpts() {
		$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'The most recent comments' ) );
		$this->WP_Widget('recent-comments-excerpts', __('Recent Comments + Excerpts'), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array(&$this, 'recent_comments_style') );

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function recent_comments_style() { ?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	function flush_widget_cache() {
		wp_cache_delete('recent_comments', 'widget');
	}

	function widget( $args, $instance ) {
		global $wpdb, $comments, $comment;

		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments') : $instance['title']);
		if ( !$number = (int) $instance['number'] )
			$number = 5;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 150 )
			$number = 150;

		if( !$excerptLen = (int) $instance['excerptLen'] )
			$excerptLen = 50;
		else if ( $excerptLen < 1 )
			$excerptLen = 1;

		if( 0 + $instance['showAdmin'] ){
			$showAdmin = 1;
		} else{
			$showAdmin = 0 + $instance['showAdmin'];
		}

		if ( !$comments = wp_cache_get( 'recent_comments', 'widget' ) ) {
			$comments = "SELECT $wpdb->comments.* FROM $wpdb->comments JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID ";
			$comments .= "WHERE comment_approved = '1' AND post_status = 'publish' ";
			if( !$showAdmin ){
				$comments .= "AND user_id != 1 ";
			}
			$comments .= "ORDER BY comment_date_gmt DESC LIMIT 150";
			//echo $comments;
			$comments = $wpdb->get_results( $comments );
			wp_cache_add( 'recent_comments', $comments, 'widget' );
		}

		$comments = array_slice( (array) $comments, 0, $number );
?>
		<?php echo $before_widget; ?>
			<?php if ( $title ) echo $before_title . $title . $after_title; ?>
			<ul id="recentcomments"><?php
			if ( $comments ) : foreach ( (array) $comments as $comment) :
			//echo  '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s on %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . get_the_title($comment->comment_post_ID) . '</a>') . '</li>';
				$aRecentComment = get_comment($comment->comment_ID);
				$aRecentCommentTxt = trim( mb_substr( strip_tags( apply_filters( 'comment_text', $aRecentComment->comment_content )), 0, $excerptLen ));
				if( strlen( $aRecentComment->comment_content ) > $excerptLen ){
					$aRecentCommentTxt .= "...";
				}
			echo  '<li class="recentcomments">' . /* translators: comments widget: 1: comment author, 2: post link */ sprintf(_x('%1$s said %2$s', 'widgets'), get_comment_author_link(), '<a href="' . esc_url( get_comment_link($comment->comment_ID) ) . '">' . $aRecentCommentTxt . '</a>') . '</li>';
			endforeach; endif;?></ul>
		<?php echo $after_widget; ?>
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['excerptLen'] = (int) $new_instance['excerptLen'];
		$instance['showAdmin'] = (bool) $new_instance['showAdmin'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments']) )
			delete_option('widget_recent_comments');

		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent comments';
		$number = isset($instance['number']) ? absint($instance['number']) : 8;
		$excerptLen = isset($instance['excerptLen']) ? absint($instance['excerptLen']) : 50;
		$showAdmin = isset($instance['showAdmin']) ? 0 + $instance['showAdmin'] : 1;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br />
		<small><?php _e('(at most 150)'); ?></small></p>

		<p><label for="<?php echo $this->get_field_id('excerptLen'); ?>"><?php _e('Character length of excerpt:'); ?></label>
		<input id="<?php echo $this->get_field_id('excerptLen'); ?>" name="<?php echo $this->get_field_name('excerptLen'); ?>" type="text" value="<?php echo $excerptLen; ?>" size="3" /><br /></p>

		<p><input id="<?php echo $this->get_field_id('showAdmin'); ?>" name="<?php echo $this->get_field_name('showAdmin'); ?>" type="checkbox" value="1" <?php if( $showAdmin ){ echo " checked"; } ?> />
		<label for="<?php echo $this->get_field_id('showAdmin'); ?>"><?php _e('Show comments by admin'); ?></label></p>

<?php
	}
}

function WP_Widget_Recent_Comments_Excerpts_Init() {
	register_widget('WP_Widget_Recent_Comments_Excerpts');
}
add_action('widgets_init', 'WP_Widget_Recent_Comments_Excerpts_Init');
?>