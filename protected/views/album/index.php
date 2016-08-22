<?php
/* @var $this AlbumController */

$this->breadcrumbs=array(
	'Album',
);
?>

<style type="text/css">
	/*input[type="text"]{
		width: 200px;
		height: 25px;
		line-height: 25px;
	}*/
</style>
<div class="am-g">
	<!-- <div class="am-g">
      <div class="am-u-sm-12 am-u-md-6">
        <div class="am-btn-toolbar">
          <div class="am-btn-group am-btn-group-xs">
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button>
            <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>
          </div>
        </div>
      </div>
    </div> -->
    <div class="am-g">
      <div class="am-form-group am-form-group-lg am-u-sm-6">
	    <div class="am-u-sm-10">
	      <input type="text" id="album_name" class="am-form-field" placeholder="输入相册名字">
	    </div>
	    <div class="am-u-sm-2 am-form-label">
	    	<a href="javascript:void(0);" id="albumSubmit" class="am-btn am-btn-primary">添加相册</a>
	    </div>
	  </div>
	</div>

	<!-- <div class="create">
		<input type="text" id="album_name"  class="am-input-lg" /> <a href="javascript:void(0);" id="albumSubmit" class="am-btn am-btn-primary">添加相册</a>
	</div> -->

	<?php if ($album) { ?>
		<table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">标题</th><th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <?php foreach ($album->results as $key => $value) {?>
					<tr>
		              <td><input type="checkbox" /></td>
		              <td><?php echo $key+1;?></td>
		              <td><a href="<?php echo $this->createUrl('basketball/photoList', array('album' => $value->objectId));?>" target="_blank"><?php echo $value->name;?></a></td>
		              <td>
		                <div class="am-btn-toolbar">
		                  <div class="am-btn-group am-btn-group-xs">
		                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button>
		                    <!-- <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 复制</button> -->
		                    <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><span class="am-icon-trash-o"></span> 删除</button>
		                  </div>
		                </div>
		              </td>
		            </tr>
				<?php }?>
            </tbody>
        </table>
	<?php }?>
</div>
<script type="text/javascript">
	$("#albumSubmit").click(function(){
		if ($("#album_name").val()=="") {
			alert('请输入相册标题');
			return false;
		}else{
			$.ajax({
					type: 'POST',
					url: "<?php echo $this->createUrl('album/create');?>",
					data: {name: $("#album_name").val()},
					dataType: "json",
					success: function(data) {
						 alert(data.code);
					},
					error: function(XHR) {
						return false;
					}
				});
		}
	});
</script>
