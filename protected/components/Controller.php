<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $allowType = array("image/jpg", "image/jpeg", "image/png", "image/pjpeg", "image/gif", "image/bmp", "image/x-png");

	public function makeDataMessage($code = 0, $msg = '', $data = array(),$json=true) {
		$result = array('head'=>array('code'=>$code, 'msg'=>$msg));

		//判断data为空时不创建进json数据中

		if (!is_null($data)) {
			// $result['body']=$data;
			$result = array_merge($result, $data);
		}
		
		if (!$json) {
			return $result;
		}
		
		return CJSON::encode($result);
	}

	/**
	 * 检验请求的参数的格式是否正确
	 * 将$req的json字符串内容转换成php对象
	 * 检验通过返回转换后的php对象，否则输出错误信息
	 * json例子{"name":"liangbo","year":15,"activity":[{"name":"liangbo2"}]}
	 */
	protected function checkRequestParam($req)
	{
		if (is_null($req) || empty($req)) {
			//当请求参数为空时
			echo $this->makeDataMessage(ERR_PARAMETER_INVALID,MSG_PARAMETER_INVALID,null);
			exit;
		}

		$requestParam=json_decode($req);

		// 验证JSON格式
		if (json_last_error() != JSON_ERROR_NONE) {
			switch (json_last_error()) {
		        case JSON_ERROR_DEPTH:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Maximum stack depth exceeded',null);
		        break;
		        case JSON_ERROR_STATE_MISMATCH:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Underflow or the modes mismatch',null);
		        break;
		        case JSON_ERROR_CTRL_CHAR:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Unexpected control character found',null);
		        break;
		        case JSON_ERROR_SYNTAX:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Syntax error, malformed JSON',null);
		        break;
		        case JSON_ERROR_UTF8:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Malformed UTF-8 characters, possibly incorrectly encoded',null);
		        break;
		        default:
		            echo $this->makeDataMessage(ERR_JSONFORMAT,'Unknown error',null);
		        break;
		    }

		    Yii::app()->end();
		}

		return $requestParam;
	}

	protected function createImagePathWithExtension($extension, $destDir="/images/photo/bigthumb/"){
		
		return $destDir.$this->createUuid().'.'.$extension;
	}

	protected function saveImage($uploadedFile, $filename){
		$destFile = Yii::app()->params['imageDirectoryBasePath'] . $filename;
		$destPath = dirname($destFile);
		
		// 创建图片子目录。
		if (!file_exists($destPath)) {
			if(false === mkdir($destPath, 0755, true)){
				throw new CHttpException(403, '没有图片目录操作权限 ');
			}		
		}

		$uploadedFile->saveAs($destFile);
	}

	/**
	* 生成缩略图
	* @param $filename,$thumbnailFilename,$thumbnailWidth,$thumbnailHeight
	* 参数说明
	* 		filename 原图路径
	* 		thumbnailFilename 缩略图路径
	* 		thumbnailWidth 缩略图宽度
	* 		thumbnailHeight 缩略图高度
	* @author liangbo
	*/
	protected function createThumbnail($filename, $thumbnailFilename, $thumbnailWidth, $thumbnailHeight)
	{
		$absoluteFilename = Yii::app()->params['imageDirectoryBasePath'] . $filename;
		$image = Yii::app()->image->load($absoluteFilename);
		
		/** 
		 * resize 缩放原图的尺寸，Image::WIDTH 按宽度缩放
		 * crop 按设置的宽和高裁剪图片
		 */
		$image->resize($thumbnailWidth, $thumbnailHeight, Image::WIDTH)->crop($thumbnailWidth, $thumbnailHeight)->quality(80)->sharpen(25);
		$absoluteThumbnail = Yii::app()->params['imageDirectoryBasePath'] . $thumbnailFilename;
		$image->save($absoluteThumbnail);
	}

	/**
	* 生成缩略图不进行裁剪
	* @param $filename,$thumbnailFilename,$thumbnailWidth,$thumbnailHeight
	* 参数说明
	* 		filename 原图路径
	* 		thumbnailFilename 缩略图路径
	* 		thumbnailWidth 缩略图宽度
	* 		thumbnailHeight 缩略图高度
	* @author liangbo
	*/
	protected function createThumbnailNotCrop($filename, $thumbnailFilename, $thumbnailWidth, $thumbnailHeight)
	{
		$absoluteFilename = Yii::app()->params['imageDirectoryBasePath'] . $filename;
		$image = Yii::app()->image->load($absoluteFilename);
		
		/** 
		 * resize 缩放原图的尺寸，Image::WIDTH 按宽度缩放
		 * crop 按设置的宽和高裁剪图片
		 */
		$image->resize($thumbnailWidth, $thumbnailHeight, Image::WIDTH)->quality(80)->sharpen(25);
		$absoluteThumbnail = Yii::app()->params['imageDirectoryBasePath'] . $thumbnailFilename;
		$image->save($absoluteThumbnail);
	}

	/**
	* 生成缩略图不进行裁剪和缩放压缩等
	* @param $filename,$thumbnailFilename
	* 参数说明
	* 		filename 原图路径
	* 		thumbnailFilename 缩略图路径
	* @author liangbo
	*/
	protected function createThumbnailNotCropNotResize($filename, $thumbnailFilename)
	{
		$absoluteFilename = Yii::app()->params['imageDirectoryBasePath'] . $filename;
		$image = Yii::app()->image->load($absoluteFilename);
		
		/** 
		 * resize 缩放原图的尺寸，Image::WIDTH 按宽度缩放
		 * crop 按设置的宽和高裁剪图片
		 */
		$image->quality(75)->sharpen(25);
		$absoluteThumbnail = Yii::app()->params['imageDirectoryBasePath'] . $thumbnailFilename;
		$image->save($absoluteThumbnail);
	}

	/**
	 * 删除图片
	 * @param $filePath 图片路径
	 * @author liangbo 
	 */
	protected function deleteImage($filePath)
	{
		if (!empty($filePath)) {
			$originFile = Yii::app()->params['imageDirectoryBasePath'] . $filePath;
			if (file_exists($originFile)) {
				unlink($originFile);
			}
		}
	}

	/**
	 * 上传图片格式,大小验证
	 * @param $uploadedFile
	 * @author liangbo 
	 */
	protected function validateImage($uploadedFile){
		/* 设置允许上传文件的类型 */
		$type = $uploadedFile->type;
		if (!in_array($type, $this->allowType)) {
			echo CJSON::encode(array('msg'=>'','error'=>'图片格式不对，请重新上传!'));
			Yii::app()->end();
		}
		/* 设置允许上传文件的大小 */
		$filesize=$uploadedFile->getSize();
		if ($filesize>1048576) {
			echo CJSON::encode(array('msg'=>'','error'=>'图片太大!'));
			Yii::app()->end();
		}
	}

	/**
	 * 生成UUID做为文件名
	 * @author liangbo
	 */
	function createUuid() {     
	    return str_replace('.', '', uniqid(null,true));
	}
}