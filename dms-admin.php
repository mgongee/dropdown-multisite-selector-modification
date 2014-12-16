<?php

$name="";
$options;
$multisite;
$placeholder;

$options_db_name = 'dms2_dms_select_name';
$options_db_select = 'dms2_dms_options';
$options_db_multisite = 'dms2_dms_multisite';
$options_db_placeholder = 'dms2_dms_placeholder';

if(get_option( $options_db_name ) ){
	$name = get_option( $options_db_name );
	if($name =='false'){
		$name = "";
	}
}

if (get_option( $options_db_select )) {
	$options = get_option( $options_db_select );
}

if (get_option( $options_db_multisite )) {
	$multisite = get_option( $options_db_multisite );
}

if (get_option( $options_db_placeholder )) {
	$placeholder = get_option( $options_db_placeholder );
}
else {
	$placeholder = __('Select option','dropdown-multisite-selector');
}
?>

<div class="dms-admin">
	
	<h1>Dropdown Multisite Selector 2</h1>
	
	<div class="dms-description">
		
		<h2><?php _e('Description','dms2_dropdown-multisite-selector');?></h2>
		
		<p> <?php _e('This plugin allows you to add a simple select filed to your page giving the option to the user to switch between different urls/pages/sites. In the fields below just fill the requiered information. "Option Name" is the name that will be seen by the user, and "URL to redirect" is the place you would like to redirect your visitor when selecting the relevant option.');?></p>
		<p> <?php _e('The Wordpress Multisite Network(WMN) Options gives you the option for creating an automatic list of options. <b>Show all sites in the WMN</b> is for a list with all the sites from the WMN, while the <b>Show only the sites where the user is registered in the WMN</b> is only for those sites where the logged in user is already registered - it is a nice and easy way to navigate throug your multisite.','dropdown-multisite-selector'); ?></p>
		<p> <?php _e('To add the select option on a page you can use this shortcode <code>[dms]</code>. If you would like to include it in a php file - use <code>&lt;?php echo do_shortcode(\'[dms]\'); ?&gt;</code>','dropdown-multisite-selector');?></p>
		<p><?php _e('Please note that if you use a multisite and your site url is different from your wordpress url there the main site url will as the wordpress site url for fixing this issue check the <a href="http://codex.wordpress.org/Changing_The_Site_URL">wordpress codex</a>.','dropdown-multisite-selector') ?></p>
		<p><?php _e('For any support or bug reporting please use this <a href="http://wordpress.org/support/plugin/dropdown-multisite-selector">link</a>') ?></p>
	
	</div>

	<div class="dms-formular">
		<h2><?php _e('Options','dms2_dropdown-multisite-selector');?></h2>
		<p class="succes_log"></p>
		<p class="overall-error"></p>
		<form action="" id="dms-all-inputs">
		
			<fieldset>
				<legend><?php _e('Dropdown settings','dms2_dropdown-multisite-selector');?></legend>
				<p class="error-log-name"></p>
				<label for="select_name"><?php _e('The select tag label','dms2_dropdown-multisite-selector');?></label></br>
				<input type="text" id="select_name" value="<?php echo $name; ?>" name="select_name"></br>
				<label for="select_placeholder"><?php _e('The first option to show in the select element (for instance "Select site", "Select Option")','dms2_dropdown-multisite-selector');?></label></br>
				<input type="text" id="select_placeholder" value="<?php echo $placeholder; ?>" name="select_placeholder"></br>
			</fieldset>
			
			<fieldset class="middle-box">
				<legend><?php _e('Dropdown fields','dms2_dropdown-multisite-selector');?></legend>
				<div <?php if($multisite!='none' && $multisite){echo 'class="mask-middle"';} ?> ></div>
				<p class="error-log-fields"></p>
				<table id="dms2_dms-table">
					<thead>
						<tr>
							<th><?php _e('Option name','dms2_dropdown-multisite-selector');?></th>
							<th><?php _e('URL to redirect','dms2_dropdown-multisite-selector');?></th>
						</tr>
					<thead>
					<tbody>

						<?php 
						if ( is_array($options) ) {
							$k = 1;

							foreach ( $options as $key => $value ) {?>

								<tr class="new-row">
									<td>
										<input type="text" value="<?php echo $key; ?>" name="field_name">
									</td>
									<td>
										<input type="text" value="<?php echo $value; ?>"  class="urls" name="field_url">
									</td>
									<td>
										<input type="button" class="del_row" value=" X ">
									</td>
								</tr>
							<?php
								$k++;
							}	
						}
						else{ ?>
							<tr class="new-row">
								<td>
									<input type="text" name="field_name">
								</td>
								<td>
									<input type="text" name="field_url">
								</td>
								<td>
									<input type="button" class="del_row" value=" X ">
								</td>
							</tr>

						<?php }?>

					</tbody>
				</table>

				<input type="button" value="<?php _e('Add field','dms2_dropdown-multisite-selector');?>" name="add-field" id="dms2_dms-add">

			</fieldset>
			
			<fieldset>
				
				<legend><?php _e('Wordpress Multisite Network(WMN) Options','dms2_dropdown-multisite-selector'); ?></legend>

				<input id="radio-none" type="radio" name="multisite" <?php if ($multisite=='none' || ! $multisite) {echo 'checked="checked"';} ?> value="none"><?php _e('Disabled','dms2_dropdown-multisite-selector'); ?><br>
				<input id="radio-all" type="radio" name="multisite" <?php if ($multisite=='all') {echo 'checked="checked"';} ?> value="all"><?php _e('Show all sites in the WMN.','dms2_dropdown-multisite-selector'); ?><br>
				<input id="radio-users" type="radio" name="multisite" <?php if ($multisite=='usersonly') {echo 'checked="checked"';} ?> value="usersonly"><?php _e('Show only the sites where the user is registered in the WMN.','dms2_dropdown-multisite-selector'); ?><br>

			</fieldset>

				<input type="submit" value="<?php _e('Save','dms2_dropdown-multisite-selector');?>" name="submit" id="dms2_dms-submit">
				
				
		
		</form>
		 
	</div>
	

</div>
