<?php
/* @var $this BasketballController */

$this->breadcrumbs=array(
	'Basketball',
);

$typeList = array(0 => "传球", 1 => "运球", 2 => "上蓝", 3 => "投篮", 4 => "进攻", 5 => "防守", 6 => "篮板", 7 => "弹跳练习", 8 => 'NBA教学');
?>

<script type="text/javascript" src="js/ajaxfileupload.js"></script>

<style type="text/css">
.container{
	width: 100%;
}
img{
	display: block;
	border: 0;
	width: 200px;
}
.dialog-row{
	display: block;
	height: 40px;
	line-height: 40px;
	margin: 10px 0px;
}
#videoName{
	width: 250px;
	height: 30px;
}
 .input-h-30{
	width: 340px;
	height: 30px;
}
input[type="file"]{
	width: 200px;
	outline: none;
	outline-style: none;
}
#videoType{
	height: 28px;
	width: 200px;
}
.create-video{
	margin: 10px;
}
#createLoadVideoDialog .dialog-row>span{
	display: inline-block;
}
#videoUrlHidden{
	width: 400px;
}
#videoSummary{
	height: 200px;
	outline-style: none;
	outline: none;
	width: 300px;
	/*margin-bottom: 50px;*/
}
#loadVideoSummary{
	height: 100px;
	width: 300px;
	outline-style: none;
	outline: none;
}
</style>

	<div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <button type="button" class="am-btn am-btn-default" onclick="$('#createVideoDialog').dialog('open');"><span class="am-icon-plus"></span> 新增</button>
            <button type="button" class="am-btn am-btn-default" onclick="$('#createLoadVideoDialog').dialog('open');"><span class="am-icon-plus"></span> 本地上传</button>
            <!-- <a class="am-btn am-btn-default" href="<?php echo $this->createUrl('video/uploadUpYun');?>"><span class="am-icon-plus"></span> 本地上传</a> -->
            <!-- <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button> -->
          </div>
        </div>
      </div>
      <form action="<?php echo $this->createUrl('basketball/index');?>" method="POST" id="videoTypeForm">
      	<div class="am-u-sm-12 am-u-md-3">
	        <div class="am-form-group top-select">
	        <!-- data-am-selected="{btnSize: 'sm'}" -->
	          <select id="videoType" name="videoType">
	          <?php foreach ($videoTypeList as $key => $value) {
	          	if ($curVideoType == $value->objectId) {
	          		echo '<option value="'.$value->objectId.'" selected>'.$value->name.'</option>';
	          	}else{
	          		echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
	          	}
	          }?>
	          </select>
	        </div>
	      </div>
      </form>
    <!--   <div class="am-u-sm-12 am-u-md-3">
        <div class="am-input-group am-input-group-sm">
          <input type="text" class="am-form-field">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button">搜索</button>
          </span>
        </div>
      </div> -->
    </div>

<!-- <div class="create-video">
	<a href="javascript:$('#createVideoDialog').dialog('open');">添加视频</a>
</div> -->

<?php 
	$this->beginWidget('zii.widgets.jui.CJuiDialog',
       array(
               'id' => 'mydialog',
               'options' =>array(
                       'title' => '修改视频',
                       'width' => '500px',
                       'height' => 500,
                       'autoOpen' => false,
                       'buttons' => array(  
						            '保存' => 'js:function(){updateVideo();}',
						            '关闭'=>'js:function(){ $(this).dialog("close");}',//关闭按钮
						        ),  
               ),
       ));?>

       <div class="dialog-row">
	       	<span>视频名称：</span>
	       	<span><input type="text" id="videoName"/></span>
       </div>
       <div class="dialog-row">
	       	<span>视频分类：</span>
	       	<span>
		       	<select id="videoType">
		       		<?php foreach ($videoTypeList as $key => $value) {
		       			if ($curVideoType == $value->objectId) {
			          		echo '<option value="'.$value->objectId.'" selected>'.$value->name.'</option>';
			          	}else{
			          		echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
			          	}
		       		} ?>
		       	</select>
	       	</span>
       </div>
        <div class="dialog-row">
	       	<span>视频简介：</span>
	       	<span><textarea id="videoSummary" name="videoSummary"></textarea></span>
       </div>
       <input type="hidden" id="objectId" value="">
<?php 
       $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php if ($results) {?>
	<table class="am-table am-table-striped am-table-hover table-main">
		<thead>
		<th>序号</th>
		<th>名称</th>
		<th>描述</th>
		<th>图片</th>
		<th>视频</th>
		</thead>
		<tbody>
			<?php foreach ($results->results as $key => $value) {?>
				<tr>
					<td><?php echo $key+1;?></td>
					<td><?php echo $value->name;?></td>
					<td><?php echo isset($value->summary) ? $value->summary : "无";?></td>
					<td><img src="<?php echo $value->cover;?>"></td>
					<td><a href="javascript:openDialog('<?php echo $value->objectId;?>','<?php echo $value->name;?>','<?php echo $value->videoType->objectId;?>', '<?php echo isset($value->summary) ? $value->summary : "";?>');">修改</a></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
<?php }?>


<!--添加视频部分-->

<?php 
	$this->beginWidget('zii.widgets.jui.CJuiDialog',
       array(
               'id' => 'createVideoDialog',
               'options' =>array(
                       'title' => '添加视频',
                       'width' => '500px',
                       'height' => 'auto',
                       'autoOpen' => false,
                       'buttons' => array(  
						            '保存' => 'js:function(){createVideo();}',
						            '关闭'=>'js:function(){ $(this).dialog("close");}',//关闭按钮
						        ),  
               ),
       ));?>

       <div class="dialog-row">
	       	<span>源url：</span>
	       	<span><input type="text" id="sourceUrl" class="input-h-30" /></span>
	       	<span><a href="javascript:checkVideo();" title="检查视频是否有mp4源">检查</a></span>
       </div>
       <div class="dialog-row">
	       	<span>视频名称：</span>
	       	<span><input type="text" id="CVideoName" class="input-h-30" /></span>
       </div>
       <div class="dialog-row">
	       	<span>图片url：</span>
	       	<span><input type="text" id="imgUrl" class="input-h-30" /></span>
       </div>
       <div class="dialog-row">
	       	<span>视频分类：</span>
	       	<span>
		       	<select id="videoType2">
		       		<?php foreach ($videoTypeList as $key => $value) {
			       			echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
			       		} ?>
		       	</select>
	       	</span>
       </div>
       <div class="dialog-row">
	       	<span>视频专辑：</span>
	       	<span>
		       	<select id="special">
		       	<option value="-1">请选择</option>
		       		<?php foreach ($specialList as $key => $value) {
			       			echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
			       		} ?>
		       	</select>
	       	</span>
       </div>
       <div class="dialog-row">
		       	<span>视频简介：</span>
		       	<span><textarea id="youkuVideoSummary" name="youkuVideoSummary"></textarea></span>
	       </div>
       <div class="dialog-row">
	       	<span>缩略图：</span>
	       	<span><img id="thumb" src="" width="50px" height="50px" /></span>
       </div>
       
       <input type="hidden" id="objectId" value="">
       <input type="hidden" id="seconds" value="">
<?php 
       $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<!--添加本地视频部分-->

<?php 
	$this->beginWidget('zii.widgets.jui.CJuiDialog',
       array(
               'id' => 'createLoadVideoDialog',
               'options' =>array(
                       'title' => '本地视频',
                       'width' => '600px',
                       'height' => 700,
                       'autoOpen' => false,
                       'buttons' => array(  
						            '保存' => 'js:function(){createLoadVideo();}',
						            '关闭'=>'js:function(){ $(this).dialog("close");}',//关闭按钮
						        ),  
               ),
       ));?>
	       <div class="dialog-row">
		       	<span>源url：</span>
		       	<span><input type="file" name="loadSourceUrl" id="loadSourceUrl" /></span>
		       	<span><a href="javascript:ajaxFileUpload();" class="am-btn am-btn-default" title="">上传</a></span>
	       </div>
	        <div class="dialog-row">
		       	<span>视频：</span>
		       	<span><input type="text" name="videoUrlHidden" id="videoUrlHidden" value="http://lbbus.video.buddysoft.cn/video/" /></span>
	       </div>
	       <div class="dialog-row">
		       	<span>视频名称：</span>
		       	<span><input type="text" id="loadCVideoName" class="input-h-30" /></span>
	       </div>
	       <div class="dialog-row">
		       	<span>图片上传</span>
		       	<span><input type="file" name="loadimg" id="loadimg" /></span>
		       	<span><a href="javascript:ajaxImageUpload();" class="am-btn am-btn-default" title="">上传</a></span>
	       </div>
	       <div class="dialog-row">
		       	<span>时长：</span>
		       	<span><input type="text" name="loadseconds" id="loadseconds" value="" /></span>
	       </div>
	       <div class="dialog-row">
		       	<span>图片url：</span>
		       	<span><input type="text" id="loadImgUrl" class="input-h-30"  value="http://lbbus.video.buddysoft.cn/images/" /></span>
	       </div>
	       <div class="dialog-row">
		       	<span>视频分类：</span>
		       	<span>
			       	<select id="loadVideoType2">
			       		<?php foreach ($videoTypeList as $key => $value) {
			       			echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
			       		} ?>
			       	</select>
		       	</span>
	       </div>
	       <div class="dialog-row">
		       	<span>视频专辑：</span>
		       	<span>
			       	<select id="loadSpecial">
			       	<option value="-1">请选择</option>
			       		<?php foreach ($specialList as $key => $value) {
				       			echo '<option value="'.$value->objectId.'">'.$value->name.'</option>';
				       		} ?>
			       	</select>
		       	</span>
	       </div>
	       <div class="dialog-row">
		       	<span>视频简介：</span>
		       	<span><textarea id="loadVideoSummary" name="loadVideoSummary"></textarea></span>
	       </div>
	       <div class="dialog-row">
		       	<span>缩略图：</span>
		       	<span><img id="loadThumb" src="" width="50px" height="50px" /></span>
	       </div>
<?php 
       $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script type="text/javascript">
	
	function openDialog(objId, vide_name, vide_type, summary)
	{
		$("#videoName").val(vide_name);
		$("#videoType").val(vide_type);
		$("#objectId").val(objId);
		$("#videoSummary").val(summary);
		$('#mydialog').dialog('open');
	}

	function updateVideo()
	{
		$.ajax({
				type: 'POST',
				url: "<?php echo $this->createUrl('basketball/updateVideo');?>",
				data: {objectId: $("#objectId").val(), videoName: $("#videoName").val(), videoType: $("#videoType").val(), videoSummary: $("#videoSummary").val()},
				dataType: "json",
				success: function(data) {
					 // alert(data.code);
					 // $("#videoSummary").val('');
					 $('#mydialog').dialog('close');
				},
				error: function(XHR) {
					return false;
				}
			});
	}

	// 检查视频是否有mp4源
	function checkVideo()
	{
		$.ajax({
				type: 'GET',
				url: "<?php echo $this->createUrl('video/checkVideo');?>",
				data: {url: $("#sourceUrl").val()},
				dataType: "json",
				success: function(data) {

					 if (data) {
					 	if (data.high != undefined || data.normal != undefined) {
					 		$("#CVideoName").val(data.title);
					 		$("#imgUrl").val(data.img);
					 		$("#thumb").attr('src', data.img);
					 		$("#seconds").val(data.seconds);

					 		return false;
					 	}else{
					 		alert('此视频不支持mp4');
					 	}
					 }
				},
				error: function(XHR) {
					return false;
				}
			});
	}

	// 添加视频
	function createVideo()
	{
		$.ajax({
				type: 'POST',
				url: "<?php echo $this->createUrl('video/create');?>",
				data: {videoUrl: $("#sourceUrl").val(), videoName: $("#CVideoName").val(), imgUrl: $("#imgUrl").val(), type: $("#videoType2").val(), 
				special:$("#special").val(), duration: $("#seconds").val(), videoSummary: $("#youkuVideoSummary").val()},
				dataType: "json",
				success: function(data) {

					 if (data) {
					 	if (data.code == 0) {
					 		alert('添加成功');
					 		$("#CVideoName").val('');
					 		$("#sourceUrl").val('');
					 		$("#imgUrl").val('');
					 		$("#thumb").attr('src', '');
					 		$("#youkuVideoSummary").val('');
					 	}else{
					 		alert('添加失败');
					 	}
					 }
				},
				error: function(XHR) {
					return false;
				}
			});
	}

	$(function(){
		$("#videoType").change(function(){
			$("#videoTypeForm").submit();
		});
	});

	function ajaxFileUpload()
	{
	    $.ajaxFileUpload
	    (
	        {
	            url:"<?php echo $this->createUrl('video/uploadUpYun');?>",
	            secureuri:false,
	            fileElementId:'loadSourceUrl',
	            dataType: 'json',
	            success: function (data, status)
	            {
	            	alert(data.code);
	                if(typeof(data.msg) != 'undefined')
	                {
	                    $("#videoUrlHidden").val(data.msg);
	                }
	                return false;
	            },
	            error: function (data, status, e)
	            {
	                alert(e);
	            }
	        }
	    )

	    return false;
	}

	function ajaxImageUpload()
	{ 
	    $.ajaxFileUpload
	    (
	        {
	            url:"<?php echo $this->createUrl('video/uploadUpYunImg');?>",
	            secureuri:false,
	            fileElementId:'loadimg',
	            dataType: 'json',
	            success: function (data, status)
	            {
	            	alert(data.code);
	                if(typeof(data.msg) != 'undefined')
	                {
	                    $("#loadImgUrl").val(data.msg);
	                }
	            },
	            error: function (data, status, e)
	            {
	                alert(e);
	            }
	        }
	    )

	    return false;
	}

	// 本地视频2
	function createLoadVideo()
	{
		$.ajax({
				type: 'POST',
				url: "<?php echo $this->createUrl('video/create');?>",
				data: {videoUrl: $("#videoUrlHidden").val(), videoName: $("#loadCVideoName").val(), imgUrl: $("#loadImgUrl").val(), type: $("#loadVideoType2").val(), 
				special:$("#loadSpecial").val(), source:"半点",  duration: $("#loadseconds").val(), videoSummary: $("#loadVideoSummary").val()},
				dataType: "json",
				success: function(data) {

					 if (data) {
					 	if (data.code == 0) {
					 		alert('添加成功');
					 		$("#loadCVideoName").val('');
					 		$("#videoUrlHidden").val('http://lbbus.video.buddysoft.cn/video/');
					 		$("#loadImgUrl").val('http://lbbus.video.buddysoft.cn/images/');
					 		$("#loadThumb").attr('src', '');
					 		$("#loadVideoSummary").val('');
					 	}else{
					 		alert('添加失败');
					 	}
					 }
				},
				error: function(XHR) {
					return false;
				}
			});
	}
	

</script>
