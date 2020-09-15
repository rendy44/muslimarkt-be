<?php
/**
 * Footer email wrapper template.
 *
 * @author Rendy
 * @package Muslimarkt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

									</td>
								</tr>
							</table>
						</td>
					</tr>

					<?php
					/**
					 * Muslimarkt after global email template hook.
					 */
					do_action( 'muslimarkt_after_global_email_template' );
					?>

					<!-- END MAIN CONTENT AREA -->
				</table>
				<!-- END CENTERED WHITE CONTAINER -->

				<!-- START FOOTER -->
				<div class="footer">
					<table role="presentation" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td class="content-block powered-by">
								<?php
								/* translators: %s : current web name */
								echo sprintf( __( 'Dikirim oleh %s', 'muslimarkt' ), get_bloginfo( 'name' ) ); // phpcs:ignore
								?>
							</td>
						</tr>
					</table>
				</div>
				<!-- END FOOTER -->
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</body>
</html>