<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<div class="am-cf am-padding">
  <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> </div>
</div>

<ul class="am-avg-sm-1 am-avg-md-4 am-margin am-padding am-text-center admin-content-list ">
  <li><a href="#" class="am-text-success"><span class="am-icon-btn am-icon-file-text"></span><br/>视频<br/><?php echo $videoCount;?></a></li>
  <li><a href="#" class="am-text-warning"><span class="am-icon-btn am-icon-briefcase"></span><br/>篮球宝贝<br/><?php echo $photoCount;?></a></li>
  <li><a href="#" class="am-text-danger"><span class="am-icon-btn am-icon-recycle"></span><br/>相册<br/><?php echo $albumCount;?></a></li>
  <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-user-md"></span><br/>分类<br/>8</a></li>
</ul>



<div class="am-g">
       
</div>