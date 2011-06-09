<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="leader">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_team('team'); ?></ul>
	</div>
	<div id="content" class="clear mainwide">
        <div class="clear box">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
					<h2>编辑项目的商户券信息</h2>
					<ul class="filter"><?php echo current_manageteam('editvoucher', $team['id']); ?></ul>
				<!--{/if}-->
				</div>
                <div class="sect">
					<div class="wholetip clear"><h3>1、上传商户券信息</h3></div>
					<form method="post" action="/manage/team/editvoucher.php?id=<?php echo $team['id']; ?>" enctype="multipart/form-data" class="validator">
					<input type="hidden" name="id" value="<?php echo $team['id']; ?>" />
					<div class="field">
						<label>商户券文件</label>
						<input type="file" size="30" name="upload_txt" class="f-input" style="width:300px;"/> <input type="submit" value="上传商户券" name="commit" id="leader-submit" class="formbutton" style="margin-left:120px;"/>
						<span class="hint">文本文件，每行一个券</span>
					</div>
					</form>

					<div class="wholetip clear"><h3>2、商户券列表&nbsp;&nbsp;<a href="/manage/team/ajax.php?action=removeteamvoucher&id=<?php echo $team['id']; ?>" class="ajaxlink" ask="确定清空本项目的商户券吗？">清空商户券</a></h3></div>
					<table id="orders-list" cellspacing="0" cellpadding="0" border="0" class="coupons-table">
					<tr><th width="200">商户券编码</th><th width="200">用户</th><th width="380">订单</th><th width="100" nowrap>操作</th></tr>
					<?php if(is_array($vouchers)){foreach($vouchers AS $index=>$one) { ?>
					<tr <?php echo $index%2?'':'class="alt"'; ?> id="order-list-id-<?php echo $one['id']; ?>">
						<td><?php echo $one['code']; ?></td>
						<td nowrap><?php if($one['user_id']){?><?php echo $users[$one['user_id']]['email']; ?><br/><?php echo $users[$one['user_id']]['username']; ?><?php } else { ?>NULL<?php }?></td>
						<td><?php if($one['order_id']){?><a href="/ajax/manage.php?action=orderview&id=<?php echo $one['order_id']; ?>" class="ajaxlink">订单详情</a><?php } else { ?>NULL<?php }?></td>
						<td class="op"><a href="/manage/team/ajax.php?action=removeonevoucher&vid=<?php echo $one['id']; ?>" class="ajaxlink" ask="确定删除本商户券?">删除</a>&nbsp;</td>
					</tr>
					<?php }}?>
					<tr><td colspan="4"><?php echo $pagestring; ?></tr>
                    </table>
                </div>
            </div>
            <div class="box-bottom"></div>
        </div>
	</div>
<div id="sidebar">
</div>

</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("manage_footer");?>
