<span class="tve_options_headline"><span class="tve_icm tve-ic-move"></span><?php echo __( "Thrive Click to call options", "thrive-cb" ) ?></span>
<ul class="tve_menu">
	<?php include dirname( __FILE__ ) . '/_margin.php' ?>
	<li class="tve_btn_text">
		<label class="tve_text">
			<?php echo __( "Call to Action Text", "thrive-cb" ) ?> <input type="text" class="tve_change" id="custom_phone_text"/>
		</label>
		<label class="tve_text">
			<?php echo __( "Mobile Call to Action Text", "thrive-cb" ) ?> <input type="text" class="tve_change" id="custom_mobile_phone_text"/>
		</label>
	</li>
	<li class="tve_btn_text">
		<label>
			<?php echo __( "Mobile Color Button", "thrive-cb" ) ?>
			<select class="tve_change" id="custom_phone_color">
				<option value="default"><?php echo __( "Default", "thrive-cb" ) ?></option>
				<option value="blue"><?php echo __( "Blue", "thrive-cb" ) ?></option>
				<option value="green"><?php echo __( "Green", "thrive-cb" ) ?></option>
				<option value="red"><?php echo __( "Red", "thrive-cb" ) ?></option>
				<option value="purple"><?php echo __( "Purple", "thrive-cb" ) ?></option>
				<option value="orange"><?php echo __( "Orange", "thrive-cb" ) ?></option>
			</select>
		</label>
		<label class="tve_text">
			<?php echo __( "Phone Number", "thrive-cb" ) ?> <input type="text" class="tve_change" id="custom_phone_no"/>
		</label>
	</li>
</ul>