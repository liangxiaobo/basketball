<?php $this->layout = 'mobileMain'; ?>
<style type="text/css">
    .source{
        display: -webkit-box;
        -webkit-box-orient:horizontal;
        -webkit-box-align: center;
        display: -ms-flexbox;
        -ms-flex-orient:horizontal;
        -ms-flex-align: center;
    }
    .txt{
        font-size: 0.9em;
        display: block;
        color: rgb(113,118,121);
        width: 70px;
        -webkit-box-flex:1;
        -ms-flex:1;
    }
    .play-count{
        display: block;
        font-size: 0.9em;
        color: rgb(113,118,121);
        background: url(./images/icon_plays.png) no-repeat 0px 10px;
        background-size: 11px 10px;
        -webkit-box-flex:0;
        padding-left: 15px;
    }

</style>
<div id="youkuplayer" style=""></div>
    <script type="text/javascript" src="http://player.youku.com/jsapi">
        player = new YKU.Player('youkuplayer',{
            styleid: '0',
            client_id: '554011cfa501ba09',
            vid: '<?php echo $vid;?>',
            embsig: 'VERSION_TIMESTAMP_SIGNATURE',
            show_related: false,
            events:{
                onPlayStart: function(){ 
                    location.href = "playvideo-iOS:#";
                }
            }
        });
    </script>
<div class="source"><span class="txt">(视频来源：优酷)</span><span class="play-count"><?php echo $playCount;?></span></div>
