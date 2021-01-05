<?php
/**
 * BuddyBoss - Video Single Album
 *
 * @package BuddyBoss\Core
 *
 * @since BuddyBoss 1.0.0
 */

global $video_album_template;

$album_id      = (int) bp_action_variable( 0 );
$album_privacy = bp_video_user_can_manage_album( $album_id, bp_loggedin_user_id() );
$can_manage    = true === (bool) $album_privacy['can_manage'];
$can_add       = true === (bool) $album_privacy['can_add'];
if ( bp_has_video_albums( array( 'include' => $album_id ) ) ) : ?>
	<?php
	while ( bp_video_album() ) :
		bp_video_the_album();

		$total_video = $video_album_template->album->video['total'];
		?>
		<div id="bp-video-single-album">
			<div class="album-single-view" <?php echo 0 === $total_video ? esc_attr( 'no-videos' ) : ''; ?>>

				<div class="bb-single-album-header text-center">
					<h4 class="bb-title" id="bp-single-album-title"><?php bp_video_album_title(); ?></h4>
					<?php if ( ( bp_is_my_profile() || bp_current_user_can( 'bp_moderate' ) ) || ( bp_is_group() && $can_manage ) ) : ?>
						<input type="text" value="<?php bp_video_album_title(); ?>" placeholder="<?php esc_html_e( 'Title', 'buddyboss' ); ?>" id="bb-album-title" style="display: none;" />
						<a href="#" class="button small" id="bp-edit-album-title"><?php esc_html_e( 'edit', 'buddyboss' ); ?></a>
						<a href="#" class="button small" id="bp-save-album-title" style="display: none;" ><?php esc_html_e( 'save', 'buddyboss' ); ?></a>
						<a href="#" class="button small" id="bp-cancel-edit-album-title" style="display: none;" ><?php esc_html_e( 'cancel', 'buddyboss' ); ?></a>
					<?php endif; ?>
					<p>
						<span><?php bp_core_format_date( $video_album_template->album->date_created ); ?></span>
						<span class="bb-sep">&middot;</span>
						<span>
						<?php
							printf(
								/* translators: videos */
								_n( '%s video', '%s videos', $video_album_template->album->video['total'], 'buddyboss' ),
								number_format_i18n( $video_album_template->album->video['total'] )
							);
						?>
								</span>
					</p>
				</div>

				<?php
				if ( ( ( bp_is_my_profile() || bp_is_user_video() ) && $can_manage ) || ( bp_is_group() && $can_manage ) ) :
					?>

					<div class="bb-album-actions">
						<a class="bb-delete button small outline error" id="bb-delete-album" href="#">
							<?php esc_html_e( 'Delete Album', 'buddyboss' ); ?>
						</a>

						<?php
						if ( $can_add ) {
							?>
						<a class="bb-add-videos button small outline" id="bp-add-video" href="#" >
							<?php esc_html_e( 'Add Videos', 'buddyboss' ); ?>
						</a>
						<?php } ?>

						<?php if ( ( bp_is_my_profile() || bp_is_user_video() ) && ! bp_is_group() ) : ?>
							<select id="bb-album-privacy">
								<?php foreach ( bp_video_get_visibility_levels() as $k => $option ) { ?>
									<?php
									$selected = '';
									$privacy  = bp_get_album_privacy();
									if ( $k === $privacy ) {
										$selected = 'selected="selectred"';}
									?>
									<option <?php echo esc_attr( $selected ); ?> value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $option ); ?></option>
								<?php } ?>
							</select>

						<?php endif; ?>
					</div>

					<?php
						bp_get_template_part( 'video/uploader' );
				endif;

				if ( $can_manage ) {
					bp_get_template_part( 'video/actions' );
				}
				?>

				<div id="video-stream" class="video" data-bp-list="video">

					<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'album-video-loading' ); ?></div>

				</div>

			</div>
		</div>
		<?php
	endwhile;
endif; ?>