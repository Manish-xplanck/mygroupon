<!--{template @admin/header}-->

<!-- * ֧���������� * -->

{eval
    $pay = logic('pay')->SrcOne('alipay');
    $cfg = unserialize($pay['config']);
}

<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">�޸�֧��������</td>
		</tr>
		<tr>
			<td width="23%" class="td_title">֧�����˻���</td>
			<td width="77%">
				<input name="cfg[account]" type="text" value="{$cfg['account']}">
				* ( <a href="http://cenwor.com/thread-3955-1-1.html" target="_blank"><font color="red">������������Ź���֧����������ǩԼ��ַ</font></a> )
			</td>
		</tr>
		<tr>
			<td class="td_title">���װ�ȫЧ���룺</td>
			<td>
				<input name="cfg[key]" type="text" size="35" value="{$cfg['key']}" />
			</td>
		</tr>
		<tr>
			<td class="td_title">���������ID��</td>
		    <td>
		    	<input name="cfg[partner]" type="text" value="{$cfg['partner']}" />
	    	</td>
		</tr>
		<tr>
			<td class="td_title">֧���ӿ����ͣ�</td>
		    <td>
		    	<select name="cfg[service]">
		    		<option value="create_direct_pay_by_user"{if $cfg['service']=='create_direct_pay_by_user'} selected="selected"{/if}>��ʱ���ʽӿ�</option>
		    		<option value="create_partner_trade_by_buyer"{if $cfg['service']=='create_partner_trade_by_buyer'} selected="selected"{/if}>�������׽ӿ�</option>
		    		<option value="trade_create_by_buyer"{if $cfg['service']=='trade_create_by_buyer'} selected="selected"{/if}>֧����˫�ӿ�</option>
		    	</select>
		    </td>
		</tr>
		<tr>
			<td class="td_title">�Ƿ�֧��OpenSSL��</td>
		    <td>
		    	<select name="cfg[ssl]">
		    		<option value="true"{if $cfg['ssl']=='true'} selected="selected"{/if}>��</option>
		    		<option value="false"{if $cfg['ssl']=='false'} selected="selected"{/if}>��</option>
		    	</select>
				 *ע��������Ľӿ��ǵ������ף������֧��OpenSSL�ſ��Խ�������֧��
		    </td>
		</tr>
		<tr>
			<td class="tr_nav tr_center" colspan="2">
				֧��������
			</td>
		</tr>
		<tr>
			<td class="td_title">�Ƿ�����ݵ�¼��</td>
			<td><font class="ini" title="alipay.account.login.enabled"></font></td>
		</tr>
		<tr>
			<td class="td_title">�Ƿ������ٻ�ȡ�ջ���ַ��</td>
			<td><font class="ini" title="alipay.address.import.enabled"></font></td>
		</tr>
		<tr class="tips">
			<td colspan="2">
				�����û���ʹ�ô���Ӧ��֮ǰ����ȷ�����뿪ͨ��֧������ӦȨ��
				<br/>
				<a href="http://cenwor.com/thread-3955-1-1.html" target="_blank"><font color="red">�����ַ</font></a>
			</td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="submit" value="�� ��" class="button" />
	</center>
</form>
{~ui('loader')->js('#admin/js/sdb.parser')}
<!--{template @admin/footer}-->