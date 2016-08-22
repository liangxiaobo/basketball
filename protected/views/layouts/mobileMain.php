<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<meta name="language" content="en" />
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
	<meta content="yes" name="apple-mobile-web-app-capable" />
	<meta content="black" name="apple-mobile-web-app-status-bar-style" />
	<meta content="telephone=no" name="format-detection" />

	<meta name="description" content="" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<style type="text/css">
		/*body,div,section,a,p,h1,img,header,ul,li,input{
			padding: 0px;
			margin:0px;
		}*/

		body{
			-webkit-touch-callout: none;
			-webkit-overflow-scrolling:touch;
			font-family: Helvetica, sans-serif;
		}
		#youkuplayer{
        min-height: 180px;
    }
    @media screen and (max-width: 1024px) { /*当屏幕尺寸小于1024px时，应用下面的CSS样式*/
        #youkuplayer{
            width:100%;
            height:400px;
            margin: 0 auto;
        }
    }

    @media screen and (max-width: 768px) { /*当屏幕尺寸小于768px时，应用下面的CSS样式*/
        #youkuplayer{
            width:750;
            height:448px;
            margin: 0 auto;
        }
    }

    @media screen and (max-width: 414px) { /*当屏幕尺寸小于414px时，应用下面的CSS样式*/
        #youkuplayer{
            width:400px;
            height:240px;
            margin: 0 auto;
        }
    }
    @media screen and (max-width: 375px) { /*当屏幕尺寸小于1080px时，应用下面的CSS样式*/
        #youkuplayer{
            width:355px;
            height:221px;
            margin: 0 auto;
        }
    }
    @media screen and (max-width: 320px) { /*当屏幕尺寸小于320px时，应用下面的CSS样式*/
        #youkuplayer{
            width:300px;
            height:180px;
            margin: 0 auto;
        }
    }
    .source{
        height: 30px;
        line-height: 30px;
    }
	</style>
</head>

<body>
	<?php echo $content; ?>
</body>
</html>
