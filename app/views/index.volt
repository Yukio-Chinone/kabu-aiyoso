<html lang="jp">
<head>
    <?php if (strstr($_SERVER['HTTP_HOST'], 'localhost')) {?>
    <!-- Local -->
    <?php }else{?>
    <!-- Global site tag (gtag.js) - Google Analytics (解析) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135033486-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-135033486-1');
    </script>
    <!-- Google Adsense (広告) -->
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({
            google_ad_client: "ca-pub-6074202716971264",
            enable_page_level_ads: true
        });
    </script>
    <?php }?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title><?php echo $title ?></title>
    <?php if(isset($description)){?>
    <meta name="description" content="<?php echo $description;?>" />
    <?php }else{?>
    <meta name="description" content="AI株価予測ツール。人工知能(ディープラーニング)を使って日本の株式投資市場の過去の株価の動きを学習し、直近10日間の未来の株価の動きを予想する、株式投資ソフトです。毎日15時半〜19時の間に予想して、チャートに未来の株価の動きやトレンドラインを自動的に描画します。日経平均株価、東証1部、2部、マザーズの全銘柄対象" />
    <?php }?>
    <meta name="keywords" content="AI,株価予測,株式投資,人工知能,株価分析,株価予想,上がる株,下がる株,ディープラーニング,Deeplearning"/>
    <link href="/dist/css/bootstrap.min.css?<?php echo date('YmdHis')?>" rel="stylesheet"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="/form-validation.css?<?php echo date('YmdHis')?>" rel="stylesheet">
    <link href="/css/finance.css?<?php echo date('YmdHis')?>" rel="stylesheet">
    <link href="/css/pagetop.css?<?php echo date('YmdHis')?>" rel="stylesheet">

    <!-- 各デバイス用のFavicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="16×16" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="32×32" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="57×57" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="60×60" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="72×72" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="76×76" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="114×114" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="120×120" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="144×144" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="152×152" href="/favicon.png">
    <link rel="apple-touch-icon" sizes="180×180" href="/favicon.png">
    <link rel="icon" type="image/png" sizes="192×192" href="/favicon.png">
    <link data-react-helmet="true" rel="alternate" type="application/rss+xml" title="RSS" href="https://kabu.aiyoso.com/rss/rss2"/>

</head>
<body class="bg-light">
<div class="container">

    <!-- ヘッダー -->
    <?php $this->partial("partial/header")?>

    <!-- ボディ -->
    <div class="row">

        <!-- 右コンテンツ -->
        <div class="col-md-8 order-md-1">
            <?php echo $this->getContent()?>
        </div>

        <!-- 左コンテンツ -->
        <div class="col-md-4 order-md-2 mb-4">
            <?php $this->partial("partial/menu_right")?>
        </div>

    </div>

    <!-- フッター -->
    <?php $this->partial("partial/footer")?>

</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="/assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
<script src="/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-zDnhMsjVZfS3hiP7oCBRmfjkQC4fzxVxFhBx8Hkz2aZX8gEvA/jsP3eXRCvzTofP"
        crossorigin="anonymous"></script>
<script src="/form-validation.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="/js/common.js?<?php echo date('YmdHis')?>"></script>
<script src="/js/pagetop.js?<?php echo date('YmdHis')?>"></script>
<p id="PageTopBtn"><a href="#wrap">Page top</a></p>
</body>
</html>
