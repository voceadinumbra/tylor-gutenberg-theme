<?php
/**
 * The template for displaying Comments
 *
 * @package WordPress
 * @subpackage Tyler
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area">


	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			Comments
		</h2>

		<ol class="commentlist">
			<?php wp_list_comments(); ?>
		</ol><!-- .commentlist -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<div id="comment-nav-below" class="nav-paging" role="navigation">
			<div class="nav-previous pull-left"><?php previous_comments_link( __( 'Older Comments') ); ?></div>
			<div class="nav-next pull-right"><?php next_comments_link( __( 'Newer Comments') ); ?></div>
		</div>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a note.
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'tyler' ); ?></p>
		<?php endif; ?>

	<?php endif; // have_comments() ?>


    <hr/>
	<?php comment_form(array (
        'title_reply' => '',
        'fields' => array(
            'author' => '<p class="comment-form-author">' .
                '<input id="author" name="author" type="text" value="" size="30" placeholder="'. __( 'NAME' ) .'" required />' .
                '</p>',
            'email'  => '<p class="comment-form-email">' .
                '<input id="email" name="email"  type="text" value="" size="30" placeholder="'. __( 'EMAIL' ) .'" required /></p>',
            'number'    => '<p class="comment-form-number">' .
                '<input id="number" name="number" type="text" value="" size="30" placeholder="'. __( 'NUMBER' ) .'" /></p>',
        ),
        'comment_field'        => '<p class="comment-form-comment"><textarea placeholder="'. __('COMMENT') .'" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
        'label_submit'  => 'Leave comment',
        'comment_notes_before' => '',
        'comment_notes_after' => ''
    )); ?>

</div><!-- #comments .comments-area -->