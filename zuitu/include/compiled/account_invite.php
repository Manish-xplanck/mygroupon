<?php include template("header");?>
<script src="http://widgets.manyou.com/misc/scripts/ab.js" type="text/javascript"></script>
<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="referrals">
	 <div id="content" class="refers">
        <div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head"><h2>邀请有奖</h2></div>
                <div class="sect">
					<?php if($money){?>
					<p class="notice-total">您已成功邀请 <strong><?php echo $count; ?></strong> 人，累计获得 <strong><span class="money"><?php echo $currency; ?></span><?php echo moneyit($money); ?></strong> 返利，<a href="/account/refer.php">查看邀请成功列表</a>。</p>
					<?php }?>
					<p class="intro">当好友接受您的邀请，在<?php echo $INI['system']['sitename']; ?>上首次成功购买，系统会在 24 小时内返还 <?php echo $INI['system']['invitecredit']; ?> 元到您的<?php echo $INI['system']['sitename']; ?>电子账户，下次团购时可直接用于支付。没有数量限制，邀请越多，返利越多。</p>
					<div class="share-list">
						<div class="blk im">
							<div class="logo"><img src="/static/css/i/logo_qq.gif" /></div>
							<div class="info">
								<h4>这是您的专用邀请链接，请通过 MSN 或 QQ 发送给好友：</h4>
								<input id="share-copy-text" type="text" value="<?php echo $INI['system']['wwwprefix']; ?>/r.php?r=<?php echo $login_user_id; ?>" size="35" class="f-input" onfocus="this.select()" tip="复制成功，可以通过 MSN 或 QQ 发送给好友了" />
								<input id="share-copy-button" type="button" value="复制" class="formbutton" />
							</div>
						</div>
						<div class="blk">
							<div class="logo"><img src="/static/css/i/logo_msn.png" /></div>
							<div class="info finder-form">
								<h4>您可以直接邀请邮箱或MSN好友&nbsp;(<span><a href="javascript:;" onclick="MYABC.showChooser('recipients', '/account/invitemaillist.html', null, false, false, null);return false">点击邀请</a></span>)</h4>
								<div id="email_invitation" style="display:none;">
								<form action="/account/invite.php" method="post" id="finder-form" class="validator">
								 <p>
									<label for="recipients">邀请列表</label>
									<textarea name="recipients" id="recipients" class="f-input" rows="5" cols="50" require="true" datatype="require"></textarea>
								</p>

								 <p>
									<label for="invitation_content">邀请内容</label>
									<textarea name="invitation_content" id="invitation_content"  class="f-input" cols="50" rows="5" require="true" datatype="require">Hi，我最近发现了<?php echo $INI['system']['sitename']; ?>，每天都有超级划算的产品进行团购活动，快来看看吧～</textarea>
								</p>
								<p>
									<label for="real_name">您的姓名</label>
									<input id="real_name" type="text" value="<?php echo htmlspecialchars($login_user['username']); ?>" name="real_name" size="12" class="f-input"  require="true" datatype="require" />
								</p>
								<p class="commit"><input type="submit" class="formbutton" value="继续" /></p>
								</form>
								</div>
							</div>
						</div>
					<?php if($team){?>
						<div class="blk last">
							<div class="logo"><img src="/static/css/i/logo_share.gif" /></div>
							<div class="info">
								<h4>分享今日团购给好友，也可获<?php echo abs($team['bonus']); ?>元返利！</h4>
								<div class="deal-info">
									<p class="pic"><a href="/team.php?id=<?php echo $team['id']; ?>" target="_blank"><img src="<?php echo team_image($team['image']); ?>" width="150" height="90" /></a></p>
									<p class="deal-title"><?php echo $team['title']; ?></p>
								</div>
								<div id="deal-share" >
									<div class="deal-share-links">
										<h4>分享到：</h4>
										<ul class="cf">
											<li><a class="kaixin" href="<?php echo share_kaixin($team); ?>" target="_blank">开心</a></li>
											<li><a class="renren" href="<?php echo share_renren($team); ?>" target="_blank">人人</a></li>
											<li><a class="douban" href="<?php echo share_douban($team); ?>" target="_blank">豆瓣</a></li>
											<li><a class="sina" href="<?php echo share_sina($team); ?>" target="_blank">新浪微博</a></li>
	  <li><a class="tencent" href="<?php echo share_tencent($team); ?>" target="_blank">腾讯微博</a></li>								      <li><a class="email" href="<?php echo share_mail($team); ?>" id="deal-buy-mailto">邮件</a></li>
										</ul>
									</div>
								</div>
							</div>
						<?php }?>
						</div>
                    <div class="clear"></div>
				</div>
				</div>
            </div>
            <div class="box-bottom"></div>
        </div>
    </div>
    <div id="sidebar">
		<?php include template("block_side_invitetip");?>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->
 
<?php include template("footer");?>
