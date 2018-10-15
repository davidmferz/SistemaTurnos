<style>
.ui-dialog .ui-dialog-content{
	padding: 5px;
}
.ui-widget-content{
	border:none;
}
#methods .ui-widget-header{
	background: #fff;
	border: none;
	border-bottom: solid 1px #aaa;
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
	border-radius: 0;
}
</style>
<div id="methods">
	<ul>
		<li><a href="#method-1"><?php __('install_example_tab_1'); ?></a></li>
		<li><a href="#method-2"><?php __('install_example_tab_2'); ?></a></li>
		<li><a href="#method-3"><?php __('install_example_tab_3'); ?></a></li>
	</ul>
	<div id="method-1">
	<?php
	if (isset($tpl['example_1']))
	{
		?><textarea id="install-example-1" class="pj-form-field"><?php echo htmlspecialchars($tpl['example_1']); ?></textarea><?php
	}
	?>
	</div>
	<div id="method-2">
	<?php
	/*if (isset($tpl['example_2']))
	{
		?><textarea id="install-example-2" class="pj-form-field"><?php echo htmlspecialchars($tpl['example_2']); ?></textarea><?php
	}*/
	?>
		<div style="position: relative; width: 528px; margin: 10px auto 0">
			<img src="<?php echo PJ_IMG_PATH; ?>backend/preview-url.png" alt="" class="align_middle" />
			<span style="position: absolute; top: 12px; left: 100px"><?php echo PJ_INSTALL_URL; ?>book.html</span>
		</div>
	</div>
	<div id="method-3">
	<?php
	if (isset($tpl['example_3']))
	{
		?><textarea id="install-example-3" class="pj-form-field"><?php echo htmlspecialchars($tpl['example_3']); ?></textarea><?php
	}
	?>
	</div>
</div>