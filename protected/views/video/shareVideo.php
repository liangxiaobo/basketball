<?php $this->layout = 'mobileMain'; ?>
<style type="text/css">
    video{
        display: block;
        width: 100%;
        background: #000;
    }
    .title{
        font-size: 1.2em;
        color: rgb(26,65,114);
        line-height: 130%;
        padding: 10px;
    }
    footer{
        display: block;
        height: 80px;
        position: fixed;
        left: 0px;
        right: 0px;
        bottom: 0px;
    }
    footer img{
        display: block;
        border: 0px;
        width: 100%;
        /*height: 100px;*/
    }
    footer a{
        text-decoration: none;
        display: block;
    }
    * {
        -webkit-touch-callout:none;
        -webkit-user-select:none;
        -khtml-user-select:none;
        -moz-user-select:none;
        -ms-user-select:none;
        user-select:none;
        -webkit-touch-callout:none;
    }
    @media screen and (min-width: 640px) { 
        footer{
                display: block;
                height: 200px;
                position: fixed;
                left: 0px;
                right: 0px;
                bottom: 0px;
            }
    }
    @media screen and (min-width: 1100px) { /*当屏幕尺寸小于1024px时，应用下面的CSS样式*/
            #youkuplayer{
                width:100%;
                height:300px;
                margin: 0 auto;
            }
            body{
                width: 400px;
                min-height: 400px;
                margin: 0 auto;
                position: relative;
            }
            video{
                height: 300px;
            }
            footer{
                display: block;
                height: 80px;
                position: inherit;
                margin-top: 100px;
            }
    }
    .tip-div{
        width: 100%;
        height: 100%;
        position:absolute;
        background: rgba(0,0,0,0.6); 
        bottom: 0px;
        left: 0px;
        display: none;
        z-index: 9999
    }
    .tip-div .tip-div-header{
        width: 100%;
        height: 120px;
        background: rgba(255,255,255,1.00) url(./images/line-top.png);
        background-repeat: no-repeat;
        background-size: 100px;
        background-position: 90% 0;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        padding-top: 30px;
        display: block;
    }
    .tip-div .tip-div-header p
    {
        line-height: 25px;
        padding: 0;
        margin: 0;
    }
    .tip-div .tip-div-header>div,.tip-div .tip-div-header>p{
        display: block;
        width: 300px;
        margin: 0 auto;
    }
    .tip-div .tip-div-header>div>p:nth-child(1){
        font-size: 1.2em;
    }
    .tip-div .tip-div-header>div>p:nth-child(2)>i{
        color: #666;
    }
    .tip-div .tip-div-header>p>span{
        font-size: 1.1em;
        color: red;
    }
</style>
<div class="tip-div" id="tip_div" onclick="this.style.display='none';">
    <div class="tip-div-header">
        <div>
            <p>链接打不开?</p>
            <p>请点击右上角 </i></p>
        </div>
        <p>选择<span>"在浏览器中打开"</span>,再点击连接</p>
    </div>
</div>
<?php if (!empty($result['url'])) {
    $this->pageTitle = $result['title'];
    ?>
    <?php if (!empty($result['vid'])) {?>
            <div id="youkuplayer" style=""></div>
            <script type="text/javascript" src="http://player.youku.com/jsapi">
                player = new YKU.Player('youkuplayer',{
                    styleid: '0',
                    client_id: '554011cfa501ba09',
                    vid: '<?php echo $result["vid"];?>',
                    embsig: 'VERSION_TIMESTAMP_SIGNATURE',
                    show_related: false,
                    events:{
                        onPlayStart: function(){ 
                            sendAjaxRequest('<?php echo $this->createUrl("video/playCount", array("objectId"=>$result["objectId"], "url"=>urlencode($result["url"])));?>');
                        }
                    }
                });
            </script>
    <?php }else {?>
            <video id="mainvideo" preload="true" poster="<?php echo $result['cover'];?>" webkit-playsinline="webkit-playsinline" controls="controls" src="<?php echo $result['url'];?>"></video>
            <script type="text/javascript">
                video = document.getElementById("mainvideo");
                video.addEventListener("play", function(){
                    sendAjaxRequest('<?php echo $this->createUrl("video/playCount", array("objectId"=>$result["objectId"], "url"=>urlencode($result["url"])));?>');
                }, false);
            </script>
        <?php }?>
<?php }?>
<div class="title">
    <?php echo $result['title'];?>
</div>
<footer>
    <a href="https://itunes.apple.com/app/id981418716" onclick="download();"><img src="./images/page_video_share.fw_r6_c1.jpg"></a>
</footer>

<script type="text/javascript">
    function is_weixn(){
            var ua = navigator.userAgent.toLowerCase();
            if(ua.match(/MicroMessenger/i)=="micromessenger") {
                return true;
            } else {
                return false;
            }
    }
    function download(){
        if (is_weixn()) {
            document.getElementById('tip_div').style.display='block';
            return false;
        }   
        return true;
    };

    var XMLHttpReq;
    function createXMLHttpRequest() {
        try {
            XMLHttpReq = new ActiveXObject("Msxml2.XMLHTTP");//IE高版本创建XMLHTTP
        }
        catch(E) {
            try {
                XMLHttpReq = new ActiveXObject("Microsoft.XMLHTTP");//IE低版本创建XMLHTTP
            }
            catch(E) {
                XMLHttpReq = new XMLHttpRequest();//兼容非IE浏览器，直接创建XMLHTTP对象
            }
        }

    }
    function sendAjaxRequest(url) {
        createXMLHttpRequest();                                //创建XMLHttpRequest对象
        XMLHttpReq.open("get", url, true);
        XMLHttpReq.onreadystatechange = processResponse; //指定响应函数
        XMLHttpReq.send(null);
    }
    //回调函数
    function processResponse() {
        if (XMLHttpReq.readyState == 4) {
            if (XMLHttpReq.status == 200) {
                var text = XMLHttpReq.responseText;
                // alert(text);
            }
        }

    }

</script>
