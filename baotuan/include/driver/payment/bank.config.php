<!--{template @admin/header}-->

<!-- * ���л�� * -->

{eval
    $pay = logic('pay')->SrcOne('bank');
    $cfg = unserialize($pay['config']);
}
{~ui('loader')->addon('editor.kind')}
<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">���л����������</td>
		</tr>
		<tr>
			<td width="3%" bgcolor="#F8F8F8">����:</td>
			<td width="97%" bgcolor="#FFFFFF">
				<textarea id="editor" name="cfg[content]" style="width:100%;">{$cfg['content']}</textarea>
			</td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="addsubmit" value="�� ��" class="button" />
	</center>
</form>
<script type="text/javascript">
$(document).ready(function(){
	KE.show({id:'editor'});
});
</script>
<!--{template @admin/footer}-->