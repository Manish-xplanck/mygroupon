<?php if($team['id']){?>
<div id="deal-share">
	<div class="deal-share-top">
		<div class="deal-share-links" style="width: 590px;">
			<h4>分享到：</h4>
			<ul class="cf">
				<li><a class="im" href="javascript:void(0);"
					id="deal-share-im-line"
					onclick="jQuery('.deal-share-<?php echo $team['id']; ?>').toggle();">MSN/QQ</a>
				</li>
				<li><a class="kaixin" href="<?php echo share_kaixin($team); ?>"
					target="_blank">开心</a>
				</li>
				<li><a class="renren" href="<?php echo share_renren($team); ?>"
					target="_blank">人人</a>
				</li>
				<li><a class="douban" href="<?php echo share_douban($team); ?>"
					target="_blank">豆瓣</a>
				</li>
				<li><a class="sina" href="<?php echo share_sina($team); ?>" target="_blank">新浪微博</a>
				</li>
				<li><a class="tencent" href="<?php echo share_tencent($team); ?>"
					target="_blank">腾讯微博</a>
				</li>
				<li><a class="email" href="<?php echo share_mail($team); ?>"
					id="deal-buy-mailto">邮件</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="deal-share-fix"></div>
	<div id="deal-share-im-c" class="deal-share-<?php echo $team['id']; ?>" style="width: 620px;">
		<div class="deal-share-im-b">
			<h3>复制下面的内容后通过 MSN 或 QQ 发送给好友：</h3>
			<p><input id="share-copy-text" type="text" value="<?php echo $INI['system']['wwwprefix']; ?>/team.php?id=<?php echo $team['id']; ?>&r=<?php echo $login_user_id; ?>" size="30" class="f-input" tip="复制成功，请粘贴到你的MSN或QQ上推荐给您的好友"/> <input id="share-copy-button" type="button" value="复制" class="formbutton" /></p>
		</div>
	</div>
</div>
<?php }?>
