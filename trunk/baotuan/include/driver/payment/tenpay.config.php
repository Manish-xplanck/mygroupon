<!--{template @admin/header}-->

<!-- * �Ƹ�ͨ������ * -->

{eval
    $pay = logic('pay')->SrcOne('tenpay');
    $cfg = unserialize($pay['config']);
}

<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">�޸ĲƸ�ͨ����</td>
		</tr>
		<tr>
			<td class="td_title">�Ƹ�ͨ��Կ��</td>
			<td class="td_content">
				<input name="cfg[key]" type="text" size="35" value="{$cfg['key']}" />
			</td>
		</tr>
		<tr>
			<td class="td_title">�Ƹ�ͨ�̻��ţ�</td>
			<td class="td_content">
				<input name="cfg[bargainor]" type="text" value="{$cfg['bargainor']}">
			</td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="submit" value="�� ��" class="button" />
	</center>
</form>

<!--{template @admin/footer}-->