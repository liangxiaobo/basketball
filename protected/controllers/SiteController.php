<?php

include_once 'leancloud-php-library/AV.php';
class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

		$videoObj = new AVQuery('Video');
		$videoObj->whereExists('objectId');
		$videoResult = $videoObj->getCount();
		$videoCount = !empty($videoResult->count)? $videoResult->count : 0;

		$photoObj = new AVQuery('Photo');
		$photoObj->whereExists('objectId');
		$photoResult = $photoObj->getCount();
		$photoCount = !empty($photoResult->count)? $photoResult->count : 0;

		$albumObj = new AVQuery('Album');
		$albumObj->whereExists('objectId');
		$albumResult = $albumObj->getCount();
		$albumCount = !empty($albumResult->count)? $albumResult->count : 0;

		$this->render('index', array('videoCount' => $videoCount, 'photoCount' => $photoCount, 'albumCount' => $albumCount));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	public function actionTest1()
	{
		// $obj = new AVObject('Comment');
	 //    $obj->content = "测试内容";
	 //    $obj->parentId = "0";

	 //    $obj->user = $obj->dataType('pointer', array('_User','54f95f0ce4b06c41dfe0e99b'));
	 //    $obj->video = $obj->dataType('pointer', array('Video','54f95ad4e4b06c41dfe07914'));
	 //    $save = $obj->save();

	 //    echo json_encode($save);
	}

	public function actionTest2()
	{
		// $t = time()+3600*8;
		// $tget = $t-3600*24*7;
		// $start = date("Ymd",$tget);
		// $end = date('Ymd');

		// $q = new AVStatistics();
		// $reslut = $q->findAppmetrics('iOS', 'new_user,active_user,device_os,device_model', $start, $end);

		$obj = new AVObject("ChannelSubject");
		$obj->content = "提问先注明：年龄,身体素质,优点（擅长）缺点（短板）全场,半场,水泥,塑胶,木地板,需要什么球鞋,明星打法,伤病史,针对各种提问,如有建议,请指点。";
		$obj->status = -1;
		// $obj->increment("commentCount", 1);
		$obj->user = $obj->dataType('pointer', array('_User', '54f95f0ce4b06c41dfe0e99b'));
		$obj->channel = $obj->dataType('pointer', array('Channel', '557689c9e4b0f9862a08ab12'));
		$result = $obj->save();

		$obj_channel = new AVObject("Channel");
		$obj_channel->increment("subjectCount", 1);
		$obj_channel->increment("todaySubjectCount", 1);
		$obj_channel->update('557689c9e4b0f9862a08ab12');

		echo json_encode($result);
	}

	public function actionTest3()
	{

		// $test_url = "https://sandbox.itunes.apple.com/verifyReceipt";
		// $production_url = "https://buy.itunes.apple.com/verifyReceipt";
		// $params = {};
		// $params['receipt-data'] = $receipt;

		// $result = http_request (HTTP_METH_GET, 'http://www.lanqiu.com/index.php?r=video/detail&url=http://v.youku.com/v_show/id_XNTUzNDU3ODg4.html?from=y1.2-1-98.3.5-1.1-1-1-4');
		// $result = http_get('www.baidu.com');

		// echo $reslut;

	

			 //设置附加HTTP头
			 // $addHead = array(
			 // "Content-type: application/json"
			 // );
			 // //初始化curl，当然，你也可以用fsockopen代替
			 // $curl_obj = curl_init();
			 // //设置网址
			 // curl_setopt($curl_obj, CURLOPT_URL, "http://www.lanqiu.com/index.php?r=video/detail&url=http://v.youku.com/v_show/id_XNTUzNDU3ODg4.html?from=y1.2-1-98.3.5-1.1-1-1-4'");
			 // //附加Head内容
			 // curl_setopt($curl_obj, CURLOPT_HTTPHEADER, $addHead);
			 // //是否输出返回头信息
			 // curl_setopt($curl_obj, CURLOPT_HEADER, 0);
			 // //将curl_exec的结果返回
			 // curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);
			 // // curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $body);
			 // //设置超时时间
			 // curl_setopt($curl_obj, CURLOPT_TIMEOUT, 15);
			 // curl_setopt($curl_obj, CURLOPT_CUSTOMREQUEST, "GET");
			 // //执行
			 // $result = curl_exec($curl_obj);
			 // //关闭curl回话
			 // curl_close($curl_obj);
			 
			 // echo $result;
	}

}