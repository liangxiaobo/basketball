<?php

include_once 'leancloud-php-library/AV.php';

class BasketballController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delPhoto', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			// array('allow',  // allow all users to perform 'index' and 'view' actions
			// 	'actions'=>array('index'),
			// 	'users'=>array('*'),
			// ),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('addPhoto', 'index', 'updateVideo', 'photoList', 'photoUpdate', 'upload', 'updatePhotoObj', 'delPhoto'),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionAddPhoto()
	{
		// 获取相册列表
		$albumQuery = new AVQuery('Album');
        $albumResults = $albumQuery->find();

		$uploadedFile = CUploadedFile::getInstanceByName('fileToUpload');
		if (!empty($uploadedFile)) {
			
			$extension = $uploadedFile->getExtensionName();
			$filename = $this->createImagePathWithExtension($extension,'/images/photo/bigthumb/');
			// $mthumbnailFilename = $this->createImagePathWithExtension($extension,'/images/photo/middlethumb/');
			$sthumbnailFilename = $this->createImagePathWithExtension($extension,'/images/photo/smallthumb/');

			// 执行上传
			$this->saveImage($uploadedFile, $filename);
			list($width, $height) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $filename);
			$this->createThumbnailNotCrop($filename, $sthumbnailFilename, 400, 0);
			list($swidth, $sheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $sthumbnailFilename);

			$bfilename = $this->createImagePathWithExtension($extension,'/images/photo/bigthumb/');

			if ($width > 640) {
				$this->createThumbnailNotCrop($filename, $bfilename, 640, 0);
				list($bwidth, $bheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $bfilename);
			}else{
				$this->createThumbnailNotCropNotResize($filename, $bfilename);
				list($bwidth, $bheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $bfilename);
			}

			$obj = new AVObject('Photo');
		    $obj->oUrl = Yii::app()->params['imgHost'].$filename;
		    $obj->sUrl = Yii::app()->params['imgHost'].$sthumbnailFilename;
		    $obj->album = isset($_POST['album']) ? $_POST['album'] : '';
		    $obj->sWidth = $swidth;
			$obj->sHeight = $sheight;
			$obj->oWidth = $width;
			$obj->oHeight = $height;
		    $obj->bWidth = $bwidth;
			$obj->bHeight = $bheight;
			$obj->bUrl = Yii::app()->params['imgHost'].$bfilename;
		    $save = $obj->save();


		    $this->render('addPhoto', array('album' => $albumResults, 'selectAlbum' => $_POST['album']));

		}else{
			$this->render('addPhoto', array('album' => $albumResults));
		}
	}

	public function actionIndex()
	{
		$videoType = isset($_POST['videoType']) ? $_POST['videoType'] : '55320607e4b0faa17ac0a57f';
		$query = new AVQuery('Video');
        $query->where('videoType', $query->dataType('pointer', array('VideoType', $videoType)));
        $results_video = $query->find();
        // print_r($results_video);exit;
		$videoTypeObject = new AVQuery('VideoType');
		$results = $videoTypeObject->find();
		$special_object = new AVQuery('Special');
		$special_results = $special_object->find();
		// print_r($special_results);exit;
		
		$this->render('index', array("results" => $results_video, 'videoTypeList' => $results->results, 
			'specialList' => $special_results->results, 'curVideoType' => $videoType));
		// $this->render('index', array("results" => $results));
	}

	// 更新视频的名称和类型
	public function actionUpdateVideo()
	{
		$msg = array('code' => -1, 'msg' => '');

		if (isset($_POST['objectId'])) {
			$updateObject = new AVObject('Video');

			if (isset($_POST['videoName'])) {
				$updateObject->name = trim($_POST['videoName']);	
			}

			if (isset($_POST['videoType'])) {
				$updateObject->videoType = $updateObject->dataType('pointer', array('VideoType', trim($_POST['videoType'])));	
			}

			if (isset($_POST['videoSummary'])) {
				$updateObject->summary = trim($_POST['videoSummary']);	
			}

			$return = $updateObject->update($_POST['objectId']);

			$msg['code'] = 0;
			// $msg['msg'] = $return;

			echo json_encode($msg);

			Yii::app()->end();
		}

		echo json_encode($msg);
	}

	public function actionPhotoList($album = "")
	{
		$query = new AVQuery('Photo');
		// $photoObj->where('album', );
		$results = null;
		if (!empty($album)) {
			$query->whereAll('album', array($album));
        	$results = $query->find();
		}

        $albumQuery = new AVQuery('Album');

        $albumResults = $albumQuery->find();

		$this->render('photoList', array('photos' => $results, 'album' => $albumResults));
	}

	// 设置相册封面
	public function actionPhotoUpdate()
	{
		$msg = array('code' => -1, 'msg' => '');

		if (isset($_POST['photoId']) && isset($_POST['albumId'])) {
			$albumObj = new AVObject('Album');
			// $updateObj->album = $_POST['albumId'];
			$albumObj->cover = $albumObj->dataType('pointer', array('Photo', $_POST['photoId']));
			$result = $albumObj->update($_POST['albumId']);

			$msg['code'] = 0;
			$msg['msg'] = $result;
			echo json_encode($msg);

			Yii::app()->end();
		}

		echo json_encode($msg);
	}

	/**
	* 上传图片
	*/
	public function actionUpload($id)
	{	
		$resultJson = array('msg' => '', 'error' => '-1');

		// 获取file组件对象
		$uploadedFile = CUploadedFile::getInstanceByName('fileToUpload');
		if (!empty($uploadedFile)) {
			
			// $this->validateImage($uploadedFile);//图片验证
			// $extension = $uploadedFile->getExtensionName();
			// $filename = $this->createImagePathWithExtension($extension,'/images/cover/');
			// $sthumbnailFilename = $this->createImagePathWithExtension($extension,'/images/cover/');

			// // 执行上传
			// $this->saveImage($uploadedFile, $filename);
			// $this->createThumbnail($filename, $sthumbnailFilename, 200, 200);
			
			// $resultJson["cover"] = $filename;
			// $resultJson["coverThumbnail"] = $sthumbnailFilename;
			// $resultJson["error"] = 0;
		}
		echo CJSON::encode($resultJson);
	}

	// 更新照片的尺寸
	public function actionUpdatePhotoObj($objId)
	{
		$msg = array('code' => -1, 'msg' => '');

		if (!empty($objId)) {
			$photoObj = new AVQuery('Photo');
			$photoObj->where('objectId', $objId); //54fe57eae4b0447de1645a41
			$result = $photoObj->find();

			if (!empty($photoObj)) {
				$surl = str_replace('http://basketball.img.buddysoft.cn', '', $result->results[0]->sUrl);
				$ourl = str_replace('http://basketball.img.buddysoft.cn', '', $result->results[0]->oUrl);
			}

			list($swidth, $sheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $surl);
			list($owidth, $oheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $ourl);

			$updateObject = new AVObject('Photo');
			$updateObject->sWidth = $swidth;
			$updateObject->sHeight = $sheight;
			$updateObject->oWidth = $owidth;
			$updateObject->oHeight = $oheight;


			// list($width, $height) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $ourl);
			$extension = pathinfo(Yii::app()->params['imageDirectoryBasePath'] . $ourl, PATHINFO_EXTENSION);
			$bfilename = $this->createImagePathWithExtension($extension,'/images/photo/bigthumb/');

			if ($owidth > 640) {
				$this->createThumbnailNotCrop($ourl, $bfilename, 640, 0);
				list($bwidth, $bheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $bfilename);
			}else{
				$this->createThumbnailNotCropNotResize($ourl, $bfilename);
				list($bwidth, $bheight) = getimagesize(Yii::app()->params['imageDirectoryBasePath'] . $bfilename);
			}
			
			$updateObject->bWidth = $bwidth;
			$updateObject->bHeight = $bheight;
			$updateObject->bUrl = Yii::app()->params['imgHost'].$bfilename;

			$return = $updateObject->update($objId);

			$msg['code'] = 0;
			// $msg['msg'] = $return;

			echo json_encode($msg);

			Yii::app()->end();
		}

		echo json_encode($msg);
	}

	// 删除图片
	public function actionDelPhoto()
	{
		$msg = array('code' => -1, 'msg' => '');
		if (isset($_POST['photoObjId'])) {
			$photoObject = new AVObject('Photo');
			$photoObject->delete($_POST['photoObjId']);
			$msg['code'] = 0;
		}

		echo json_encode($msg);
	}


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}