<?php

include_once Yii::app()->basePath.'/extensions/youku.class.php';
include_once 'leancloud-php-library/AV.php';
include_once 'php-sdk-master/upyun.class.php';

class VideoController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			// 'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('detail', 'playVideo', 'apiGetVideo', 'checkVideo', 'uploadUpYun', 'uploadLoadVideo', 'uploadUpYunImg','shareVideo', 'playCount'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create'),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionDetail($url)
	{
		// $url = "http://v.youku.com/v_show/id_XMjE5MTM4NDA0.html?from=y1.2-1-98.3.3-1.1-1-1-2";
		if (!empty($url)) {
			$data = Youku::parse($url);
			// print_r($data);
			echo json_encode($data);
		}
		// $this->render('detail');
	}

	public function actionCreate()
	{
		$msg = array('code' => -1, 'msg' => '');

		if (isset($_POST['videoName']) && isset($_POST['videoUrl']) && isset($_POST['imgUrl']) && isset($_POST['type']) ) { //   
			$obj = new AVObject('Video');
			$obj->name = $_POST['videoName'];
			$obj->cover = $_POST['imgUrl'];
			$obj->url = $_POST['videoUrl'];

			if (isset($_POST['duration'])) {
				if (!empty($_POST['duration'])) {
					$obj->duration = floatval($_POST['duration']);
				}
			}

			$obj->videoType = $obj->dataType('pointer', array('VideoType', $_POST['type']));
			$obj->status = -1;
			if (isset($_POST['source'])) {
				$obj->source = $_POST['source'];
			}

			if (isset($_POST['special']) && $_POST['special'] != "-1") {
				// $obj->special =  $_POST['special'];
				$obj->special = $obj->dataType('pointer', array('Special', $_POST['special']));
			}

			if (isset($_POST['videoSummary'])) {
				if (!empty($_POST['videoSummary'])) {
					$obj->summary = trim($_POST['videoSummary']);
				}
			}

			$obj->save();

			$msg['code'] = 0;

			echo json_encode($msg);

			Yii::app()->end();
		}

		echo json_encode($msg);
	}

	public function actionCheckVideo($url)
	{
		if (!empty($url)) {
			$data = Youku::parse($url);
			// print_r($data);
			echo json_encode($data);
		}
	}

	public function actionApiGetVideo($req)
	{
		// $msg = array('head' => array('code' => -1));
		$msg = array();

		$requestParam = $this->checkRequestParam($req);

		if (!array_key_exists("url", $requestParam)) {
			echo $this->makeDataMessage(-2, "参数为空", null);
			Yii::app()->end();
		}

		$data = Youku::parse($requestParam->url);

		if ($data) {
			// $msg['head']['code'] = 0;
			$msg['highUrl'] = $data['high'][0];
			$msg['img'] = $data['img'];

			echo $this->makeDataMessage(0, "成功", $msg);
			Yii::app()->end();
		}

		echo $this->makeDataMessage(-1, "系统出错", null);
	}

	public function actionUploadUpYun()
	{
		$msg = array('code' => -1, 'msg' => '');
		$uploadedFile = CUploadedFile::getInstanceByName('loadSourceUrl');
		if (!empty($uploadedFile)) {
			$upyun = new UpYun('basketball-buddysoft', 'liangxiaobo', 'buddysoft2015');
			$extension = $uploadedFile->getExtensionName();
			$filename = $this->createUuid().'.'.$extension;
			$fh = fopen($uploadedFile->tempName, 'rb');
		    $rsp = $upyun->writeFile('/video/'.$filename, $fh, True);   // 上传图片，自动创建目录
		    fclose($fh);
			$msg["code"] = 0;
			$msg['msg'] = 'http://lbbus.video.buddysoft.cn/video/'.$filename;
		}

		echo json_encode($msg);

	}

	public function actionUploadUpYunImg()
	{
		$msg = array('code' => -1, 'msg' => '');
		$uploadedFile = CUploadedFile::getInstanceByName('loadimg');
		if (!empty($uploadedFile)) {
			$upyun = new UpYun('basketball-buddysoft', 'liangxiaobo', 'buddysoft2015');
			$extension = $uploadedFile->getExtensionName();
			$filename = $this->createUuid().'.'.$extension;
			$fh = fopen($uploadedFile->tempName, 'rb');
		    $rsp = $upyun->writeFile('/images/'.$filename, $fh, True);   // 上传图片，自动创建目录
		    fclose($fh);
			$msg["code"] = 0;
			$msg['msg'] = 'http://lbbus.video.buddysoft.cn/images/'.$filename;
		}

		echo json_encode($msg);

	}

	public function actionPlayVideo($vid)
	{
		$playCount = 0;
		if (isset($_GET['playCount'])) {
			$playCount = $_GET['playCount'];
		}
		$this->render('playVideo', array('vid' => $vid, 'playCount' => $playCount));
	}

	// 视频分享页
	public function actionShareVideo($objectId)
	{
		$query = new AVQuery('Video');
		$query->where('objectId', $objectId);
		$results = $query->find();
		$result = [];

		if (!empty($results) && !empty($results->results)) {
			$result = $results->results[0];
		}
		$source = "";
		$vid = "";
		$url = "";
		$cover = "";
		$title = "";
		if (!empty($result)) {
			$url = $result->url;
			$cover = $result->cover;
			$title = $result->name;
		    if (isset($result->source) && $result->source == "半点") {
		    	$source = $result->source;
		    }else{
		        $regex = '/id_(.*?).html/';
		        $matches = array();
				if (preg_match($regex, $result->url, $matches)) {
					$vid = $matches[1];
				}

		     }
		}

		$this->render('shareVideo', array('result' => 
			array('source'=>$source, 'cover'=>$cover, 'url'=>$url, 'vid'=>$vid, 'title'=>$title, 'objectId'=>$objectId)));	
	}

	// 统计次数
	public function actionPlayCount($objectId, $url='')
	{
		$msg = array('code' => -1, 'msg' => '');
		$query = new AVQuery('Video');
		$query->where('objectId', $objectId);
		$results = $query->find();
		$result = [];

		if (!empty($results) && !empty($results->results)) {
			$result = $results->results[0];

			$updateObject = new AVObject('Video');
			// $updateObject->playCount = $result->playCount + 1;

			$updateObject->increment("playCount", 1);

			$updateObject->update($objectId);

			Yii::app()->runController('videoStat/add/source/share/videoObjectId/'.$objectId.'/videoUrl/'.$url);

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