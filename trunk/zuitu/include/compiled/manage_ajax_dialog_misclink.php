<div id="order-pay-dialog" class="order-pay-dialog-c" style="width:380px;">
	<h3><span id="order-pay-dialog-close" class="close" onclick="return X.boxClose();">关闭</span><?php echo $link?'编辑':'新建'; ?>友情链接</h3>
	<div style="overflow-x:hidden;padding:10px;">
	<p>网站名称、网站网址：必填</p>
<form method="post" action="/manage/misc/linkedit.php" class="validator">
	<input type="hidden" name="id" value="<?php echo $link['id']; ?>" />
	<table width="96%" class="coupons-table">
		<tr><td width="80" nowrap><b>网站名称：</b></td><td><input type="text" name="title" value="<?php echo $link['title']; ?>" require="true" datatype="require" class="f-input" /></td></tr>
		<tr><td nowrap><b>网站网址：</b></td><td><input type="text" name="url" value="<?php echo $link['url']; ?>" require="true" datatype="require" class="f-input" /></td></tr>
		<tr><td nowrap><b>LOGO地址：</b></td><td><input type="text" name="logo" value="<?php echo $link['logo']; ?>" require="require" datatype="require" class="f-input" /></td></tr>
		<tr><td nowrap><b>排序(降序)：</b></td><td><input type="text" name="sort_order" value="<?php echo intval($link['sort_order']); ?>" class="f-input" /></td></tr>
		<tr><td nowrap><b>首页显示(Y/N)：</b></td><td><input type="text" name="display" value="<?php echo $link['display']; ?>" class="f-input" style="text-transform:uppercase;"/></td></tr>
		<tr><td colspan="2" height="10">&nbsp;</td></tr>
		<tr><td></td><td><input type="submit" value="确定" class="formbutton" /></td></tr>
	</table>
</form>
	</div>
</div>
