<?php
/* @var $this BasketballController */

$this->breadcrumbs=array(
	'Basketball'=>array('/basketball'),
	'PhotoList',
);
?>

<style type="text/css">
	.photolist{
		width: 400px;
		display: block;
		margin: 0 auto;
	}
</style>

<div class="photolist">
<?php if ($photos) {
	foreach ($photos->results as $key => $value) {?>
		<div class="am-thumbnail" id="photo_el<?php echo $key;?>">
	      <img src="<?php echo $value->oUrl;?>" alt=""/>
	      <div class="am-thumbnail-caption">
	        <p>中图:<?php echo empty($value->bUrl) ? '无' : '有';?></p>
	        <p>小图:<?php echo empty($value->sUrl) ? '无' : '有';?></p>
	        <p>
	          <button class="am-btn am-btn-default" onclick="delPhoto('<?php echo $value->objectId;?>', 'photo_el<?php echo $key;?>');">删除</button>
	          <button class="am-btn am-btn-primary" onclick="change('<?php echo $value->objectId;?>', '<?php echo $value->album;?>')">设置成相册封面</button>
	        </p>
	      </div>
	    </div>
	<?php }
}?>
</div>

<script type="text/javascript">
	function delPhoto(photoId, elId)
	{
		if (photoId) {
			$.ajax({
				type: 'POST',
				url: "<?php echo $this->createUrl('basketball/delPhoto');?>",
				data: {photoObjId: photoId},
				dataType: "json",
				success: function(data) {
					 $("#"+elId).remove();
				},
				error: function(XHR) {
					return false;
				}
			});

		}
	}

	// 设置相册封面
	function change(photoId, albumId)
	{
		if (photoId) {
			if (albumId) {
				$.ajax({
					type: 'POST',
					url: "<?php echo $this->createUrl('basketball/photoUpdate');?>",
					data: {photoId: photoId, albumId: albumId},
					dataType: "json",
					success: function(data) {
						 alert(data.code);
					},
					error: function(XHR) {
						return false;
					}
				});

			}
		}
	}

	function changePhotoSize(photoId)
	{
		if (photoId) {
			$.ajax({
				type: 'GET',
				url: "<?php echo $this->createUrl('basketball/updatePhotoObj');?>",
				data: {objId: photoId},
				dataType: "json",
				success: function(data) {
					 alert(data.code);
				},
				error: function(XHR) {
					return false;
				}
			});
		}
	}
</script>