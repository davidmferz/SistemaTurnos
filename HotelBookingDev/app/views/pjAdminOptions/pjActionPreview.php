<?php 
$titles = __('error_titles', true); 
$bodies = __('error_bodies', true);
pjUtil::printNotice(@$titles['AO40'], @$bodies['AO40'], false);
?>
<ul class="preview-themes">
<?php 
foreach (range(1,10) as $i)
{
	$text = sprintf("%s %u", __('install_theme', true), $i);
	$isCurrent = (int) $tpl['option_arr']['o_theme'] == $i;
	?>
	<li>
		<?php 
		if (!$isCurrent)
		{
			?><a class="preview-thumb" href="preview.php?cid=<?php echo $controller->getForeignId(); ?>&amp;theme=<?php echo $i; ?>" target="_blank"><img src="<?php echo PJ_IMG_PATH; ?>backend/themes/<?php echo $i; ?>.jpg" alt="<?php echo $text; ?>"></a><?php
		} else {
			?><a class="preview-thumb preview-checked" href="preview.php?cid=<?php echo $controller->getForeignId(); ?>&amp;theme=<?php echo $i; ?>" target="_blank"><img src="<?php echo PJ_IMG_PATH; ?>backend/themes/<?php echo $i; ?>.jpg" alt="<?php echo $text; ?>"><i></i></a><?php
		}
		?>
		<span><a class="preview-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&action=pjActionPreview&amp;cid=<?php echo $controller->getForeignId(); ?>&amp;theme=<?php echo $i; ?>" target="_blank"><?php echo $text; ?></a></span>
		<?php 
		if (!$isCurrent)
		{
			?><span><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&action=pjActionPreview&amp;use=<?php echo $i; ?>" class="pj-button"><?php __('preview_use_theme'); ?></a></span><?php
		} else {
			?><span class="preview-current"><?php __('preview_theme_current'); ?></span><?php
		}
		?>
	</li>
	<?php
}
?>
</ul>
<?php 