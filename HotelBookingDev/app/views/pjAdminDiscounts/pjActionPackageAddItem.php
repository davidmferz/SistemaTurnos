<form action="" method="post" class="form pj-form">
	<input type="hidden" name="add_item" value="1" />
	<input type="hidden" name="package_id" value="<?php echo @$_GET['package_id']; ?>" />
	<p>
		<label class="title"><?php __('rooms_adults'); ?></label>
		<span class="inline_block">
			<input type="text" name="adults" class="pj-form-field w80" data-max="" value="1" readonly="readonly" />
		</span>
	</p>
	<p>
		<label class="title"><?php __('rooms_children'); ?></label>
		<span class="inline_block">
			<input type="text" name="children" class="pj-form-field w80" data-max="" value="0" readonly="readonly" />
		</span>
	</p>
	<p>
		<label class="title"><?php __('discount_price'); ?></label>
		<span class="pj-form-field-custom pj-form-field-custom-before">
			<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
			<input type="text" name="price" class="pj-form-field align_right w70 number required" />
		</span>
	</p>
</form>