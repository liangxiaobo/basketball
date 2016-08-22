<?php
/* @var $this VideoController */
 $this->layout = 'mobileMain';
 $this->pageTitle = "每天的统计";
?>

<style type="text/css">
	.day-item>header{
		font-size: 1.1em;
		font-weight: bold;
		/*line-height: 40px;*/
	}
	.day-item{
		margin-top: 20px;
	}
	.day-item>ul{
		margin: 0px;
		padding: 0px;
		list-style: none;
	}
	li{
		display: -webkit-box;
		-webkit-box-orient:horizontal;
		-webkit-box-align: center;
		display: -ms-flexbox;
		-ms-flex-orient:horizontal;
		-ms-flex-align: center;
		min-height: 40px;
		/*line-height: 40px;*/
		border-bottom: 1px solid rgba(102, 102, 102, 0.3);
	}
	.video-name{
		display: block;
		width: 200px;
		font-size: 0.9em;
		-webkit-box-flex:1;
		-ms-flex:1;
		line-height: 20px;
	}
	.video-share{
		display: block;
		width: 50px;
		-webkit-box-flex:1;
		-ms-flex:1;
		text-align: center;
		margin: 0px 10px;
	}
	.count{
		display: block;
		width: 50px;
		text-align: center;
		-webkit-box-flex:1;
		-ms-flex:1;
	}
	.header>a{
		display: inline-block;
		text-decoration: none;
		min-width: 60px;
		margin: 0px 10px;
	}
</style>
<div class="header">
	<a href="<?php echo $this->createUrl('videoStat/videoDayStat', array('today' => 1));?>">今天</a>
	<a href="<?php echo $this->createUrl('videoStat/videoDayStat', array('today' => 2));?>">最近7天</a>
	<a href="<?php echo $this->createUrl('videoStat/userStat', array('today' => 1));?>">用户统计</a>
</div>
<div class="row-item">
	<?php if (!empty($data)) {
		foreach ($data as $key => $value) {
			foreach ($value as $key2 => $value2) { ?>
				<div class="day-item">
				<header><?php echo $key2;?></header>
				<ul>
					<li>
						<span class="video-name">视频</span>
						<span class="video-share">分享</span>
						<span class="count">总计</span>
					</li>
					<?php foreach ($value2 as $item_key => $item_value) {?>
							<li>
								<span class="video-name"><?php echo $item_value['name'];?></span>
								<span class="video-share"><?php echo isset($item_value['share'])? $item_value['share'] : '';?></span>
								<span class="count"><?php echo $item_value['count'];?></span>
							</li>	
					<?php }?>
				</ul>
			</div>
			<?php }
	 }
	}?>
	<!-- <div class="day-item">
		<header>20150608</header>
		<ul>
			<li>
				<span class="video-name">视频</span>
				<span class="video-share">分享</span>
				<span class="count">总计</span>
			</li>
			<li>
				<span class="video-name">NBA科比教你虚晃突破上篮</span>
				<span class="video-share">3</span>
				<span class="count">3</span>
			</li>
			<li>
				<span class="video-name">NBA科比教你虚晃突破上篮</span>
				<span class="video-share">3</span>
				<span class="count">3</span>
			</li>
		</ul>
	</div>
 -->
</div>