<?php get_header(); ?>

    <div id="main">
      <div class="container">
        <div id="content">
          <div style="text-align:center;padding-bottom:100px;">
            <div id="404"><p style="font-size:96px;padding:50px 0 20px 0px;font-family:'Microsoft YaHei' ">404</p></div>
            <p style="padding:50px 0 20px 0px">页面出错或者没有此页面，将会在 <span id="mes" style="color:#3582c1;">5</span> 秒钟后返回首页！</p>
          </div>
          <script language="javascript" type="text/javascript">
           var i=5,intervalid,Url="<?php bloginfo('url');?>";
           intervalid=setInterval("fun()",1000);
           function fun(){if(i==0){window.location.href = Url;clearInterval(intervalid)}document.getElementById("mes").innerHTML=i;i--}
          </script>
        </div>
      </div>
    </div><!-- #main -->

<?php get_footer(); ?>
