<!--{template @admin/header}-->

<!-- * 财付通配置项 * -->

{eval
    $pay = logic('pay')->SrcOne('tenpay');
    $cfg = unserialize($pay['config']);
}

<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">修改财付通设置</td>
		</tr>
		<tr>
			<td class="td_title">财付通密钥：</td>
			<td class="td_content">
				<input name="cfg[key]" type="text" size="35" value="{$cfg['key']}" />
			</td>
		</tr>
		<tr>
			<td class="td_title">财付通商户号：</td>
			<td class="td_content">
				<input name="cfg[bargainor]" type="text" value="{$cfg['bargainor']}">
			</td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="submit" value="提 交" class="button" />
	</center>
</form>

<!--{template @admin/footer}-->