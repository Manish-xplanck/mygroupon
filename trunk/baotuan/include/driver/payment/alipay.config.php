<!--{template @admin/header}-->

<!-- * 支付宝配置项 * -->

{eval
    $pay = logic('pay')->SrcOne('alipay');
    $cfg = unserialize($pay['config']);
}

<form action="?mod=payment&code=save" method="post" enctype="multipart/form-data">
	<table cellspacing="1" cellpadding="4" width="100%" align="center" class="tableborder">
		<tr class="header">
			<td colspan="2">修改支付宝设置</td>
		</tr>
		<tr>
			<td width="23%" class="td_title">支付宝账户：</td>
			<td width="77%">
				<input name="cfg[account]" type="text" value="{$cfg['account']}">
				* ( <a href="http://cenwor.com/thread-3955-1-1.html" target="_blank"><font color="red">免费申请天天团购与支付宝合作的签约地址</font></a> )
			</td>
		</tr>
		<tr>
			<td class="td_title">交易安全效验码：</td>
			<td>
				<input name="cfg[key]" type="text" size="35" value="{$cfg['key']}" />
			</td>
		</tr>
		<tr>
			<td class="td_title">合作者身份ID：</td>
		    <td>
		    	<input name="cfg[partner]" type="text" value="{$cfg['partner']}" />
	    	</td>
		</tr>
		<tr>
			<td class="td_title">支付接口类型：</td>
		    <td>
		    	<select name="cfg[service]">
		    		<option value="create_direct_pay_by_user"{if $cfg['service']=='create_direct_pay_by_user'} selected="selected"{/if}>即时到帐接口</option>
		    		<option value="create_partner_trade_by_buyer"{if $cfg['service']=='create_partner_trade_by_buyer'} selected="selected"{/if}>担保交易接口</option>
		    		<option value="trade_create_by_buyer"{if $cfg['service']=='trade_create_by_buyer'} selected="selected"{/if}>支付宝双接口</option>
		    	</select>
		    </td>
		</tr>
		<tr>
			<td class="td_title">是否支持OpenSSL：</td>
		    <td>
		    	<select name="cfg[ssl]">
		    		<option value="true"{if $cfg['ssl']=='true'} selected="selected"{/if}>是</option>
		    		<option value="false"{if $cfg['ssl']=='false'} selected="selected"{/if}>否</option>
		    	</select>
				 *注：如果您的接口是担保交易，则必须支持OpenSSL才可以进行正常支付
		    </td>
		</tr>
		<tr>
			<td class="tr_nav tr_center" colspan="2">
				支付宝大快捷
			</td>
		</tr>
		<tr>
			<td class="td_title">是否开启快捷登录？</td>
			<td><font class="ini" title="alipay.account.login.enabled"></font></td>
		</tr>
		<tr>
			<td class="td_title">是否开启快速获取收货地址？</td>
			<td><font class="ini" title="alipay.address.import.enabled"></font></td>
		</tr>
		<tr class="tips">
			<td colspan="2">
				提醒用户：使用大快捷应用之前请先确认申请开通了支付宝相应权限
				<br/>
				<a href="http://cenwor.com/thread-3955-1-1.html" target="_blank"><font color="red">申请地址</font></a>
			</td>
		</tr>
	</table>
	<br/>
	<center>
		<input type="hidden" name="id" value="{$pay['id']}" />
		<input type="submit" name="submit" value="提 交" class="button" />
	</center>
</form>
{~ui('loader')->js('#admin/js/sdb.parser')}
<!--{template @admin/footer}-->