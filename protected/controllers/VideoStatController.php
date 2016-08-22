<?php

include_once 'leancloud-php-library/AV.php';

class VideoStatController extends Controller
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
				'actions'=>array('add', 'userStat','hotStat', 'videoDayStat'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	// 添加统计
	public function actionAdd($source='app', $videoObjectId='', $videoUrl='')
	{
		$tday = date('Ymd');
		$videoStat = new AVObject('VideoStat');
		$videoStat->source = $source;

		if (isset($videoUrl)) {
			$videoStat->videoUrl = urldecode($videoUrl);
		}
		$videoStat->dayDate = $tday;
		$videoStat->playCount = 1;
		$videoStat->video = $videoStat->dataType('pointer', array('Video', $videoObjectId));

		$reslut = $videoStat->save();

		echo json_encode($reslut);
	}

	// 用户统计 today,1当天、2最近7天
	public function actionUserStat($today=1)
	{	
		if ($today == 1) {
			$start = date("Ymd");
		}elseif($today == 2){
			$t = time()+3600*8;
			$tget = $t-3600*24*7;
			$start = date("Ymd",$tget);
		}

		$end = date('Ymd');

		$q = new AVStatistics();
		$reslut = $q->findAppmetrics('iOS', 'new_user,active_user,device_model', $start, $end);

		$this->render('userStat', array("data" => $reslut));
	}

	// 统计前50个视频的播放量
	public function actionHotStat()
	{
		$query = new AVQuery('Video');
		$query->orderByDescending('playCount');
		$query->whereExists('playCount');
		$query->setLimit(50);
		$results = $query->find();

		$this->render('hotStat', array("data" => $results));
	}
	
	// 每天的统计
	public function actionVideoDayStat($today=1)
	{
		$day_arr = array();

		if ($today == 1) {
			$day_arr[] = date("Ymd");
		}elseif($today == 2){
			$t = time()+3600*8;
			for ($i=1; $i <= 7; $i++) { 
				$tget = $t-3600*24*$i;
				$day_arr[] = date("Ymd",$tget);
			}
			
		}

		$query = new AVQuery('VideoStat');
		$start = date("Ymd");
		$query->whereContainedIn('dayDate', $day_arr);
		$query->whereInclude('video');
		$query->setLimit(1000);
		$results = $query->find();
		// echo json_encode($results);exit;
		$data_tmp = array();
		foreach ($day_arr as $day_arr_key => $day_arr_value) {
			
			foreach ($results->results as $result_key => $result_value) {
			 	if ($day_arr_value == $result_value->dayDate) {
			 		$data_tmp[$day_arr_value][] = $result_value;
			 	}
			 } 
		}

		// echo json_encode($data_tmp);exit;

		$data_tmp2 = array();
		foreach ($day_arr as $day_arr_key => $day_arr_value) {
			$data_tmp2_item = array();
			$data_tmp3_item = array();
			if (!empty($data_tmp[$day_arr_value])) {
				foreach ($data_tmp[$day_arr_value] as $item_key => $item_value) {
					$data_tmp2_item[$day_arr_value][$item_value->video->name]
					[$item_value->source][] = $item_value;
				}
				
				// echo json_encode($data_tmp2_item);exit;

				foreach ($data_tmp2_item[$day_arr_value] as $item_2_key => $item_2_value) {
					$result_data_item = array('name' => $item_2_key, 
						'count' => 0);

					foreach ($item_2_value as $key2 => $value2) {
						$result_data_item[$key2] = count($value2);
						$result_data_item["count"] += count($value2);
					}

					$data_tmp3_item[$day_arr_value][] = $result_data_item;
				}

				$data_tmp2[] = $data_tmp3_item;
			}
		}
		
		$this->render('videoDayStat', array("data" => $data_tmp2));
		// $data_tmp2
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