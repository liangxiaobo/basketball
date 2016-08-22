<?php
/* @var $this VideoController */
 $this->layout = 'mobileMain';
 $this->pageTitle = "leancloud视频点击量统计前50条";
 
?>

<style type="text/css">
	ul{
		padding: 0px;
		margin: 0px;
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
	.num{
		display: block;
		width: 30px;
		font-size: 0.9em;
		-webkit-box-flex:0;
	}
	.video-name{
		display: block;
		width: 200px;
		font-size: 0.9em;
		line-height: 20px;
		-webkit-box-flex:1;
		-ms-flex:1;
	}
	.play-count{
		display: block;
		width: 50px;
		text-align: right;
		-webkit-box-flex:1;
		-ms-flex:1;
	}
	.row-list>header{
		padding: 10px 0px;
		font-size: 1.1em;
		font-weight: bold;
	}
	.header>a{
		display: inline-block;
		text-decoration: none;
		min-width: 60px;
		margin: 0px 10px;
	}
</style>
<div class="header">
	<a href="<?php echo $this->createUrl('videoStat/videoDayStat', array('today' => 1));?>">每天的统计</a>
</div>
<div class="row-list">
	<header>视频点击量统计前50条</header>
	<ul>
		<?php foreach ($data->results as $key => $value) {?>
				<li>
					<span class="num"><?php echo $key+1;?></span>
					<span class="video-name"><?php echo $value->name;?></span>
					<span class="play-count"><?php echo $value->playCount>10000? ($value->playCount/10000)."<span class='unit'>万</span>" : $value->playCount;?></span>
				</li>
		<?php }?>
	</ul>
</div>