<hr/>
<h3>Mailchimp Integration</h3>
<form method="post" action="options.php">
    <?php settings_fields( 'kigoaddon_setup-group' ); ?>
    <?php do_settings_sections( 'kigoaddon_setup-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Mailchimp API key</th>
        <td><input type="text" name="kigoaddon_mailchimp_key" value="<?php echo esc_attr( get_option('kigoaddon_mailchimp_key') ); ?>" /></td>
        </tr>


				<?php
				try{
						$mailchimp_key = esc_attr( get_option('kigoaddon_mailchimp_key') );
					 	$mailchimp     = new Mailchimp($mailchimp_key);

						$lists = $mailchimp->lists->getList();

						$listVal = esc_attr( get_option('kigoaddon_mailchimp_mailing_list') ) ? esc_attr( get_option('kigoaddon_mailchimp_mailing_list') ) : '';


						?>
						<tr valign="top">
						<th scope="row">Select mailing list</th>
						<td>
						<?php
						echo '<select name="kigoaddon_mailchimp_mailing_list">';
							foreach($lists['data'] as $list)
							{

								$selected = $list['id'] == esc_attr( get_option('kigoaddon_mailchimp_mailing_list') ) ? 'selected' : '';
								echo '<option value="'.$list['id'].'" ' .$selected. ' >';
								echo $list['name'];
								echo '</option>';
							}
						echo '</select>';
						?>
					</td>
					</tr>
						<?php
					}catch(\Exception $err){
//
					}
				?>

				<tr valign="top">
        <th scope="row">Enter MailChimp form title</th>
        <td><input type="text" name="kigoaddon_mailchimp_form_title" value="<?php echo esc_attr( get_option('kigoaddon_mailchimp_form_title') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <td colspan="2" scope="row"><b>Note</b> You can insert this form in a Text Widget using: <b>[mailchimp_form]</b></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
<hr/>
