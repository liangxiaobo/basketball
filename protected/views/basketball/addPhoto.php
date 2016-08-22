<?php
/* @var $this BasketballController */

$this->breadcrumbs=array(
	'Basketball'=>array('/basketball'),
	'AddPhoto',
);
?>

<style type="text/css">
	.ablum-list{
		display: block;
		height: 30px;
		line-height: 30px;

	}
</style>

<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'dance-information-form',
			'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal', 'role' => 'form'),
			'enableAjaxValidation'=>false,

		)); ?>
		<div class="ablum-list">
			<?php if ($album) {?>
				<select id="album" name="album">
					<?php foreach ($album->results as $key => $albumvalue) {?>
						<?php if (!empty($selectAlbum) && $selectAlbum === $albumvalue->objectId) {?>
							<option value="<?php echo $albumvalue->objectId;?>" selected="selected"><?php echo $albumvalue->name;?></option>
						<?php }else {?>
						<option value="<?php echo $albumvalue->objectId;?>"><?php echo $albumvalue->name;?></option>
					<?php }}?>
				</select>
		<?php } ?>
		</div>
		<input type="file" id="fileToUpload" name="fileToUpload" />

		<button type="submit">提交</button>

<?php $this->endWidget(); ?>