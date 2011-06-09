<?php include template("manage_header");?>

<div id="bdw" class="bdw">
<div id="bd" class="cf">
<div id="help">
	<div class="dashboard" id="dashboard">
		<ul><?php echo mcurrent_vote('question'); ?></ul>
	</div>
    <div id="content" class="coupons-box clear mainwide">
		<div class="box clear">
            <div class="box-top"></div>
            <div class="box-content">
                <div class="head">
                    <h2>
						<?php if($action == 'add'){?>
							添加问题
						<?php } else { ?>
							编辑问题
						<?php }?>
					</h2>
					<ul class="filter">
						<li>
						</li>
						<li>
							<a href="question.php?action=list-is_show-1">调查中的问题列表</a>
							<a href="question.php">全部问题列表</a>
						</li>
					</ul>
				</div>
                <div class="sect">
					<form method="post" action="?action=<?php echo $action=='add' ? 'add_submit' : 'edit_submit'; ?>">
						<div class="field">
                            <label>标题</label>
                            <input type="text" size="30" name="question[title]" class="f-input" value="<?php echo $question['title']; ?>"/>
                            <span class="inputtip">不超过100个字符</span>
                        </div>
						<div class="field">
                            <label>类型</label>
                            <input type="radio" name="question[type]" value="radio" <?php if($question['type'] != 'checkbox'){?>checked<?php }?>/>单选
                            <input type="radio" name="question[type]" value="checkbox" <?php if($question['type'] == 'checkbox'){?>checked<?php }?>/>多选
                        </div>
						<div class="field">
                            <label>是否显示</label>
                            <input type="radio" name="question[is_show]" value="1" <?php if($question['is_show']){?>checked<?php }?>/>显示
                            <input type="radio" name="question[is_show]" value="0" <?php if(!$question['is_show']){?>checked<?php }?>/>隐藏
                        </div>
						<div class="field">
                            <label>排序</label>
                            <input type="text" size="30" name="question[order]" class="number" value="<?php echo $question['order']; ?>"/>
                        </div>
                        <div class="act">
							<input type="hidden" name="question[id]" value="<?php echo $question['id']; ?>"/>
                            <input type="submit" value="<?php if($action=='add'){?>添加<?php } else { ?>修改<?php }?>" name="commit" class="formbutton"/>
                        </div>
					</form>
				</div>
			</div>
            <div class="box-bottom"></div>
        </div>
    </div>
</div>
</div> <!-- bd end -->
</div> <!-- bdw end -->

<?php include template("footer");?>
