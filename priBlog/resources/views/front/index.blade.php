<!doctype html>
<html>
<head>
    <meta charset="gbk">
    <title>首页_杨青个人博客 - 一个站在web前端设计之路的女技术员个人博客网站</title>
    <meta name="keywords" content="个人博客,杨青个人博客,个人博客模板,杨青" />
    <meta name="description" content="杨青个人博客，是一个站在web前端设计之路的女程序员个人网站，提供个人博客模板免费资源下载的个人原创网站。" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('static/privateHtml/css/base.css')}}" rel="stylesheet">
    <link href="{{asset('static/privateHtml/css/index.css')}}" rel="stylesheet">
    <link href="{{asset('static/privateHtml/css/m.css')}}" rel="stylesheet">
    <script src="{{asset('static/privateHtml/js/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/privateHtml/js/jquery.easyfader.min.js')}}"></script>
    <script src="{{asset('static/privateHtml/js/conn.js')}}"></script>
    <script src="{{asset('static/privateHtml/js/nav.js')}}"></script>
    <script src="{{asset('static/privateHtml/js/scrollReveal.js')}}"></script>
    <!--[if lt IE 9]>
    <script src="js/modernizr.js"></script>
    <![endif]-->
</head>
<body>
<header>
    <!--menu begin-->
    <div class="menu">
        <nav class="nav" id="topnav">
            <h1 class="logo"><a href="http://www.yangqq.com">杨青博客</a></h1>
            <li><a href="index.html">网站首页</a> </li>
            <li><a href="about.html">关于我</a> </li>
            <li><a href="share.html">模板分享</a>
                <ul class="sub-nav">
                    <li><a href="share.html">个人博客模板</a></li>
                    <li><a href="share.html">国外Html5模板</a></li>
                    <li><a href="share.html">企业网站模板</a></li>
                </ul>
            </li>
            <li><a href="list.html">学无止境</a>
                <ul class="sub-nav">
                    <li><a href="list.html">心得笔记</a></li>
                    <li><a href="list.html">CSS3|Html5</a></li>
                    <li><a href="list.html">网站建设</a></li>
                    <li><a href="list.html">推荐工具</a></li>
                    <li><a href="list.html">JS实例索引</a></li>
                </ul>
            </li>
            <li><a href="life.html">慢生活</a>
                <ul class="sub-nav">
                    <li><a href="life.html">日记</a></li>
                    <li><a href="life.html">欣赏</a></li>
                    <li><a href="life.html">程序人生</a></li>
                    <li><a href="life.html">经典语录</a></li>
                </ul>
            </li>
            <li><a href="time.html">时间轴</a> </li>
            <li><a href="gbook.html">留言</a> </li>
            <li><a href="info.html">内容页</a> </li>
            <!--search begin-->
            <div id="search_bar" class="search_bar">
                <form  id="searchform" action="[!--news.url--]e/search/index.php" method="post" name="searchform">
                    <input class="input" placeholder="想搜点什么呢..." type="text" name="keyboard" id="keyboard">
                    <input type="hidden" name="show" value="title" />
                    <input type="hidden" name="tempid" value="1" />
                    <input type="hidden" name="tbname" value="news">
                    <input type="hidden" name="Submit" value="搜索" />
                    <span class="search_ico"></span>
                </form>
            </div>
            <!--search end-->
        </nav>
    </div>
    <!--menu end-->
    <!--mnav begin-->
    <div id="mnav">
        <h2><a href="http://www.yangqq.com" class="mlogo">杨青博客</a><span class="navicon"></span></h2>
        <dl class="list_dl">
            <dt class="list_dt"> <a href="index.html">网站首页</a> </dt>
            <dt class="list_dt"> <a href="about.html">关于我</a> </dt>
            <dt class="list_dt"> <a href="#">模板分享</a> </dt>
            <dd class="list_dd">
                <ul>
                    <li><a href="share.html">个人博客模板</a></li>
                    <li><a href="share.html">国外Html5模板</a></li>
                    <li><a href="share.html">企业网站模板</a></li>
                </ul>
            </dd>
            <dt class="list_dt"> <a href="#">学无止境</a> </dt>
            <dd class="list_dd">
                <ul>
                    <li><a href="list.html">心得笔记</a></li>
                    <li><a href="list.html">CSS3|Html5</a></li>
                    <li><a href="list.html">网站建设</a></li>
                    <li><a href="list.html">推荐工具</a></li>
                    <li><a href="list.html">JS实例索引</a></li>
                </ul>
            </dd>
            <dt class="list_dt"> <a href="#">慢生活</a> </dt>
            <dd class="list_dd">
                <ul>
                    <li><a href="life.html">日记</a></li>
                    <li><a href="life.html">欣赏</a></li>
                    <li><a href="life.html">程序人生</a></li>
                    <li><a href="life.html">经典语录</a></li>
                </ul>
            </dd>
            <dt class="list_dt"> <a href="time.html">时间轴</a> </dt>
            <dt class="list_dt"> <a href="gbook.html">留言</a> </dt>
        </dl>
    </div>
    <!--mnav end-->
</header>
<article>
    <!--banner begin-->
    <div class="picsbox">
        <div class="banner">
            <div id="ban" class="fader">
                <li class="slide" ><a href="/" target="_blank"><img src="{{asset('static/privateHtml/images/banner01.jpg')}}"><span class="imginfo">别让这些闹心的套路，毁了你的网页设计!</span></a></li>
                <li class="slide" ><a href="/" target="_blank"><img src="{{asset('static/privateHtml/images/banner02.jpg')}}"><span class="imginfo">网页中图片属性固定宽度，如何用js改变大小</span></a></li>
                <li class="slide" ><a href="/" target="_blank"><img src="{{asset('static/privateHtml/images/banner03.jpg')}}"><span class="imginfo">个人博客，属于我的小世界！</span></a></li>
                <div class="fader_controls">
                    <div class="page prev" data-target="prev">&lsaquo;</div>
                    <div class="page next" data-target="next">&rsaquo;</div>
                    <ul class="pager_list">
                    </ul>
                </div>
            </div>
        </div>
        <!--banner end-->
        <div class="toppic">
            <li> <a href="/" target="_blank"> <i><img src="{{asset('static/privateHtml/images/toppic01.jpg') }}"></i>
                    <h2>别让这些闹心的套路，毁了你的网页设计!</h2>
                    <span>学无止境</span> </a> </li>
            <li> <a href="/" target="_blank"> <i><img src= "{{asset('static/privateHtml/images/zd01.jpg')}}"></i>
                    <h2>个人博客，属于我的小世界！</h2>
                    <span>学无止境</span> </a> </li>
        </div>
    </div>
    <div class="blank"></div>
    <!--blogsbox begin-->
    <div class="blogsbox">
        <div class="blogs" data-scroll-reveal="enter bottom over 1s" >
            <h3 class="blogtitle"><a href="/" target="_blank">别让这些闹心的套路，毁了你的网页设计!</a></h3>
            <span class="blogpic"><a href="/" title=""><img src=" {{asset('static/privateHtml/images/toppic01.jpg')}}" alt=""></a></span>
            <p class="blogtext">如图，要实现上图效果，我采用如下方法：1、首先在数据库模型，增加字段，分别是图片2，图片3。2、增加标签模板，用if，else if 来判断，输出。思路已打开，样式调用就可以多样化啦！... </p>
            <div class="bloginfo">
                <ul>
                    <li class="author"><a href="/">杨青</a></li>
                    <li class="lmname"><a href="/">学无止境</a></li>
                    <li class="timer">2018-5-13</li>
                    <li class="view"><span>34567</span>已阅读</li>
                    <li class="like">9999</li>
                </ul>
            </div>
        </div>

        <div class="blogs" data-scroll-reveal="enter bottom over 1s" >
            <h3 class="blogtitle"><a href="/" target="_blank">别让这些闹心的套路，毁了你的网页设计!</a></h3>
            <span class="bplist"><a href="/" title="">
      <li><img src="{{asset('static/privateHtml/images/avatar.jpg')}} " alt=""></li>
      <li><img src=" {{asset('static/privateHtml/images/toppic02.jpg')}}" alt=""></li>
      <li><img src=" {{asset('static/privateHtml/images/banner01.jpg')}}" alt=""></li>
      </a></span>
            <p class="blogtext">如图，要实现上图效果，我采用如下方法：1、首先在数据库模型，增加字段，分别是图片2，图片3。2、增加标签模板，用if，else if 来判断，输出。思路已打开，样式调用就可以多样化啦！... </p>
            <div class="bloginfo">
                <ul>
                    <li class="author"><a href="/">杨青</a></li>
                    <li class="lmname"><a href="/">学无止境</a></li>
                    <li class="timer">2018-5-13</li>
                    <li class="view"><span>34567</span>已阅读</li>
                    <li class="like">9999</li>
                </ul>
            </div>
        </div>


        <div class="blogs" data-scroll-reveal="enter bottom over 1s" >
            <h3 class="blogtitle"><a href="/" target="_blank">帝国cms 首页或者列表页 实现图文不同样式调用方法</a></h3>
            <p class="blogtext">如图，要实现上图效果，我采用如下方法：1、首先在数据库模型，增加字段，分别是图片2，图片3。2、增加标签模板，用if，else if 来判断，输出。思路已打开，样式调用就可以多样化啦！...</p>
            <div class="bloginfo">
                <ul>
                    <li class="author"><a href="/">杨青</a></li>
                    <li class="lmname"><a href="/">学无止境</a></li>
                    <li class="timer">2018-5-13</li>
                    <li class="view">4567已阅读</li>
                    <li class="like">9999</li>
                </ul>
            </div>
        </div>

    </div>
    <!--blogsbox end-->
    <div class="sidebar">
        <div class="zhuanti">
            <h2 class="hometitle">特别推荐</h2>
            <ul>
                <li> <i><img src="{{asset('static/privateHtml/images/banner03.jpg')}}"></i>
                    <p>帝国cms调用方法 <span><a href="/">阅读</a></span> </p>
                </li>
                <li> <i><img src="{{asset('static/privateHtml/images/b04.jpg')}}"></i>
                    <p>5.20 我想对你说 <span><a href="/">阅读</a></span></p>
                </li>
                <li> <i><img src="{{asset('static/privateHtml/images/b05.jpg')}}"></i>
                    <p>个人博客，属于我的小世界！ <span><a href="/">阅读</a></span></p>
                </li>
            </ul>
        </div>



</article>
<footer>
    <p>Design by <a href="http://www.yangqq.com" target="_blank">杨青个人博客</a> <a href="/">蜀ICP备11002373号-1</a></p>
</footer>
<a href="#" class="cd-top">Top</a>
</body>
</html>
