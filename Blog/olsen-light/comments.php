<?php
	if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
		die (esc_html__('Please do not load this page directly. Thanks!', 'olsen-light'));

	if ( post_password_required() )
		return;
?>

<?php if( have_comments() || comments_open() ): ?>
	<div id="comments">
<?php endif; ?>

<?php if ( have_comments() ): ?>
	<div class="post-comments group">
		<h3><?php comments_number(); ?></h3>

		<?php $comments_pagination = paginate_comments_links( array( 'echo' => false ) ); ?>
		<?php if ( ! empty( $comments_pagination ) ): ?>
			<div class="comments-pagination"><?php echo $comments_pagination; ?></div>
		<?php endif; ?>

		<ol id="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'type'        => 'comment',
					'avatar_size' => 64
				) );
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'type'       => 'pings'
				) );
			?>
		</ol>
		<?php $comments_pagination = paginate_comments_links( array( 'echo' => false ) ); ?>
		<?php if ( ! empty( $comments_pagination ) ): ?>
			<div class="comments-pagination"><?php echo $comments_pagination; ?></div>
		<?php endif; ?>
	</div><!-- .post-comments -->
<?php endif; ?>

<?php if ( comments_open() ): ?>
	<section id="respond">
		<div id="form-wrapper" class="group">
			<?php comment_form(); ?>
		</div><!-- #form-wrapper -->
	</section>
<?php endif; ?>

<?php if( have_comments() || comments_open() ): ?>
	</div><!-- #comments -->
<?php endif; ?>
