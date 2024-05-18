<h2>My comments</h2>

<?php
/**
 * @var $user_comments
 */

if (!empty($user_comments)) {
	foreach ($user_comments as $comment) {

		$post_url = get_permalink($comment->comment_post_ID);
		$post_title = get_the_title($comment->comment_post_ID);
        ?>
        <div class="au-comment-area">
    <?php

    echo sprintf('<p class="post-link">Post: <a href="%s" target="_blank">%s</a></p>', esc_url($post_url), esc_html($post_title));

    echo sprintf('<p>Comment text: %s</p>', esc_html($comment->comment_content));

    echo sprintf(
	    '<p class="comment-date">Date: %s</p>',
	    esc_html(date('Y-m-d H:i', strtotime($comment->comment_date)))
    );

    echo sprintf('<p class="comment-author">Author: %s</p>', esc_html($comment->comment_author));

    ?>
        </div>

        <?php
	}
} else {
	echo esc_html('No comments');
}

?>
