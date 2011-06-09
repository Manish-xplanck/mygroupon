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
				<?php if($team['id']){?>
					<h2>编辑项目</h2>
					<ul class="filter"><?php echo current_manageteam('editzz', $team['id']); ?></ul>
				<?php } else { ?>
					<h2>新建项目</h2>
				<?php }?>
				</div>
                <div class="sect">
				<form id="-user-form" method="post" action="/manage/team/editzz.php?id=<?php echo $team['id']; ?>" enctype="multipart/form-data" class="validator">
					<input type="hidden" name="id" value="<?php echo $team['id']; ?>" />
					<div class="wholetip clear"><h3>1、杂项信息</h3></div>
					<div class="field">
						<label>排序</label>
						<input type="text" size="10" name="sort_order" id="team-create-sort_order" class="number" value="<?php echo $team['sort_order'] ? $team['sort_order'] : 0; ?>" datatype="number"/><span class="inputtip">请填写数字，数值大到小排序，主推团购应设置较大值</span>
					</div>
					<div class="field">
						<label>代金券使用</label>
						<input type="text" size="10" name="card" id="team-create-card" class="number" value="<?php echo moneyit($team['card']); ?>" require="true" datatype="money" />
						<span class="inputtip">可使用代金券最大面额</span>
					</div>
					<div class="field">
						<label>邀请返利</label>
						<input type="text" size="10" name="bonus" id="team-create-bonus" class="number" value="<?php echo moneyit($team['bonus']); ?>" require="true" datatype="money" />
						<span class="inputtip">邀请好友参与本单商品购买时的返利金额</span>
					</div>
					<div class="field">
						<label>消费返利</label>
						<input type="text" size="10" name="credit" id="team-create-credit" class="number" value="<?php echo moneyit($team['credit']); ?>" datatype="money" require="true" />
						<span class="inputtip">消费<?php echo $INI['system']['couponname']; ?>时，获得账户余额返利，单位CNY元</span>
					</div>
					<div class="field">
						<label>免单数量</label>
						<input type="text" size="10" name="farefree" id="team-create-farefree" class="number" value="<?php echo intval($team['farefree']); ?>" maxLength="6" datatype="money" require="true" />
						<span class="inputtip">快递费用，免单数量：0表示不免运费，2表示，购买2件免运费</span>
					</div>
					<input type="submit" value="好了，提交" name="commit" id="leader-submit" class="formbutton" style="margin:10px 0 0 120px;"/>
				</form>
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
