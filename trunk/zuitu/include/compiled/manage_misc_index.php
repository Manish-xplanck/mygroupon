<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="help">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_misc('index'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>最土软件(<?php echo SYS_VERSION; ?>_<?php echo SYS_SUBVERSION; ?>)</h2>
				</div>
                <div class="sect">
					<div class="wholetip clear"><h3>最新版本<?php echo $newversion; ?>_<?php echo $newsubversion; ?>&nbsp;[<?php if($isnew){?><span style="color:green">是</span><?php } else { ?><span style="color:red">否</span><?php }?>]&nbsp;[<a href="/manage/misc/index.php?action=db">升级数据库结构</a>]&nbsp;[<a href="/manage/misc/index.php?action=opt">优化数据库结构</a>]</h3></div>
					<div class="wholetip clear"><h3>今日数据</h3></div>
					<div style="margin:0 20px;">
						<p>今日注册用户数：<?php echo $user_today_count; ?></p>
						<p>今日团购订单数：<?php echo $order_today_count; ?>，支付订单：<?php echo $order_today_pay_count; ?>，未付订单：<?php echo $order_today_unpay_count; ?></p>
						<p>今日在线支付：<?php echo $income_pay; ?></p>
						<p>今日在线充值：<?php echo $income_charge; ?></p>
						<p>今日线下充值：<?php echo $income_store; ?></p>
						<p>今日用户提现：<?php echo $income_withdraw; ?></p>
					</div>
					<div class="wholetip clear"><h3>本周数据</h3></div>
					<div style="margin:0 20px;">
						<p>本周注册用户数：<?php echo $week[user_count]; ?></p>
						<p>本周团购订单数：<?php echo $week[order_count]; ?>，支付订单：<?php echo $week[order_pay]; ?>，未付订单：<?php echo $week[order_unpay]; ?></p>
						<p>本周在线支付：<?php echo $week[income_pay]; ?></p>
						<p>本周在线充值：<?php echo $week[income_charge]; ?></p>
						<p>本周线下充值：<?php echo $week[income_store]; ?></p>
						<p>本周用户提现：<?php echo $week[income_withdraw]; ?></p>
					</div>
					<div class="wholetip clear"><h3>统计数据</h3></div>
					<div style="margin:0 20px;">
						<p>团购项目数：<?php echo $team_count; ?></p>
						<p>用户注册数：<?php echo $user_count; ?></p>
						<p>团购订单数：<?php echo $order_count; ?></p>
						<p>邮件订阅数：<?php echo $subscribe_count; ?></p>
					</div>
				</div>
			</div>
            <div class="box-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("manage_footer");?>
