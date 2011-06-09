<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="help">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_system('upgrade'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>最土软件(<?php echo SYS_VERSION; ?>_<?php echo SYS_SUBVERSION; ?>)</h2>
				</div>
                <div class="sect">
					<div class="wholetip clear"><h3>最新版本<?php echo $newversion; ?>_<?php echo $newsubversion; ?>&nbsp;[<?php if($isnew){?><span style="color:green">是</span><?php } else { ?><span style="color:red">否</span><?php }?>]&nbsp;[<a href="/manage/system/upgrade.php?action=db">升级数据库结构</a>]</h3></div>
				<?php if($install){?>
					<div class="wholetip clear"><h3>全新安装包</h3></div>
					<div style="margin:0 20px;">
						<?php if(is_array($install)){foreach($install AS $v=>$l) { ?>
						<p><a href="<?php echo $l; ?>">ZuituGo_<?php echo $v; ?></a></p>
						<?php }}?>
					</div>
				<?php }?>
				<?php if($upgrade){?>
					<div class="wholetip clear"><h3>升级包</h3></div>
					<div style="margin:0 20px;">
					<?php if(is_array($upgrade)){foreach($upgrade AS $v=>$l) { ?>
						<p><a href="<?php echo $l; ?>">ZuituGo_Upgrade_<?php echo $v; ?></a></p>
					<?php if($upgradedesc[$v]){?>
						<p style="text-index:2em;"><?php echo $upgradedesc[$v]; ?></p>
					<?php }?>
					<?php }}?>
					</div>
				<?php }?>
				<?php if($patch){?>
					<div class="wholetip clear"><h3>补丁包</h3></div>
					<div style="margin:0 20px;">
					<?php if(is_array($patch)){foreach($patch AS $v=>$l) { ?>
						<p><a href="<?php echo $l; ?>">ZuituGo_Patch_<?php echo SYS_VERSION; ?>_<?php echo $v; ?></a></p>
					<?php if($patchdesc[$v]){?>
						<p style="text-index:2em;"><?php echo $patchdesc[$v]; ?></p>
					<?php }?>
					<?php }}?>
					</div>
				<?php }?>
					<div class="wholetip clear"><h3>升级步骤</h3></div>
					<div style="margin:0 20px;">
						<p>1、先升级数据库结构，<b>升级数据库结构不会影响现有数据</b></p>
						<p>2、下载升级包，直接覆盖现有数据，前台模板若有改动，请注意备份好你修改过的文件。</p>
						<p>3、文件覆盖后，完成升级。</p>
					</div>

					<div class="wholetip clear"><h3>软件信息</h3></div>
					<div style="margin:0 20px;">
					<?php if(is_array($software)){foreach($software AS $n=>$meta) { ?>
						<p><?php echo $n; ?>：<?php if($meta[0]=='link'){?><a href="<?php echo $meta[1]; ?>" target="_blank"><?php echo $meta[1]; ?></a><?php } else { ?><?php echo $meta[1]; ?><?php }?></p>
					<?php }}?>
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
