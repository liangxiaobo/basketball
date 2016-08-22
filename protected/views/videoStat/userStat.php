<?php
/* @var $this VideoController */
 $this->layout = 'mobileMain';
 $this->pageTitle = "leancloud统计";
 
?>
<style type="text/css">
	.new_user, .active_user, .device_model{

	}
	.new_user>ul,.active_user>ul, .device_model>ul{
		display: block;
		list-style: none;
		margin: 0px;
		padding: 0px;
	}
	.new_user>h4, .active_user>h4, .device_model>h4{
		padding: 0px;
		margin: 20px 0px;
	}
	.new_user li, .active_user li, .device_model li{
		display: -webkit-box;
		-webkit-box-orient:horizontal;
		-webkit-box-align: center;
		display: -ms-flexbox;
		-ms-flex-orient:horizontal;
		-ms-flex-align: center;
		line-height: 40px;
		border-bottom: 1px solid rgba(102, 102, 102, 0.3);
	}
	.item-date{
		display: block;
		width: 120px;
		-webkit-box-flex:0;
	}
	.item-value{
		display: block;
		-webkit-box-flex:1;
		-ms-flex:1;
		text-shadow: rgba(0, 0, 0, 0.3) 1px 0px 1px;
		text-align: right;
	}

	.active_user, .device_model{
		margin-top: 30px;
	}
	.header{
		display: block;
		margin: 20px 0px;
	}
	.header>a{
		display: inline-block;
		text-decoration: none;
		min-width: 40px;
		margin: 0px 10px;
	}

</style>

<div class="header">
	<a href="<?php echo $this->createUrl('videoStat/userStat', array('today' => 1));?>">今天</a>
	<a href="<?php echo $this->createUrl('videoStat/userStat', array('today' => 2));?>">最近7天</a>
	<a href="<?php echo $this->createUrl('videoStat/hotStat');?>">最热的前50条视频</a>
</div>

<?php foreach ($data as $itemKey => $itemValue) {?>
	<?php if ($itemValue->metrics == "new_user") {
			echo '<div class="new_user"><h4>新增用户</h4><ul>';
			foreach ($itemValue->data as $new_user_key => $new_user_value) { ?>
					<li>
						<span class="item-date"><?php echo $new_user_key;?></span>
						<span class="item-value"><?php echo $new_user_value;?></span>
					</li>
			<?php } 
			echo '</ul></div>';
			
	}elseif ($itemValue->metrics == "active_user") {
			echo '<div class="active_user"><h4>活跃用户</h4><ul>';
			foreach ($itemValue->data as $active_user_key => $active_user_value) { ?>
					<li>
						<span class="item-date"><?php echo $active_user_key;?></span>
						<span class="item-value"><?php echo $active_user_value;?></span>
					</li>
			<?php } 
			echo '</ul></div>';
	}
	/*elseif ($itemValue->metrics == "device_model") {
			echo '<div class="device_model"><h4>设备型号</h4><ul>';
			foreach ($itemValue->data as $device_model_key => $device_model_value) { ?>
					<li>
						<span class="item-date"><?php echo $device_model_key;?></span>
						<span class="item-value"><?php echo $device_model_value;?></span>
					</li>
			<?php } 
			echo '</ul></div>';
	}*/?>
<?php }?>