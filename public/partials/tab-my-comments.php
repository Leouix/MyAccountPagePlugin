<h2>Мои комментарии</h2>

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
		echo '<p class="post-link">Запись: <a href="' . esc_url($post_url) . '" target="_blank">'.$post_title.'</a></p>';
		echo '<p>Содержание: ' . $comment->comment_content . '</p>';
        echo '<p class="comment-date">Дата: ' . date('Y-m-d H:i', strtotime($comment->comment_date)) . '</p>';
		echo '<p class="comment-author">Автор: ' . $comment->comment_author . '</p>';
    ?>
        </div>

        <?php
	}
} else {
	echo 'Нет комментариев';
}

?>
