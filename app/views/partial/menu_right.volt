<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>お気に入り数</span>
    &nbsp;
    <span class="badge badge-secondary badge-pill">
        <?php echo count($userBookmarks)?>
    </span>
</h4>
<ul class="list-group mb-4">
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/bookmark/index/1" title="(買) 保有中">(買) 保有中</a>
        <strong><?php echo $userBookmarksCount[1]?></strong>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/bookmark/index/2" title="(買) 検討中">(買) 検討中</a>
        <strong><?php echo $userBookmarksCount[2]?></strong>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/bookmark/index/3" title="(売) 保有中">(売) 保有中</a>
        <strong><?php echo $userBookmarksCount[3]?></strong>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/bookmark/index/4" title="(売) 検討中">(売) 検討中</a>
        <strong><?php echo $userBookmarksCount[4]?></strong>
    </li>
</ul>

<!-- ブログ村へ -->
<a class="kabu_banner" href="https://stock.blogmura.com/stockinfo/ranking/in?p_cid=11039650" target="_blank" >
    株式情報 Blog ランキング
</a>

<a <a class="kabu_banner" href="https://stock.blogmura.com/market/ranking/in?p_cid=11039650" target="_blank" >
    相場感 Blog ランキング
</a>

<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>お気に入りランキング</span>
</h4>
<ul class="list-group mb-4">
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/ranking/index/1" title="買い銘柄">買い銘柄</a>
    </li>
    <li class="list-group-item d-flex justify-content-between lh-condensed">
        <a href="/ranking/index/2" title="売り銘柄">売り銘柄</a>
    </li>
</ul>

<h4 class="d-flex justify-content-between align-items-center mt-3 mb-1">
    <span>日経平均・ダウ平均</span>
</h4>
<div class="card">
    <div class="card-body">
        <p><b>午前05:00(日本時間)</b> に確定する<b><a href="https://nikkei225jp.com/nasdaq/" title="ダウ平均株価" target="_blank">ダウ平均株価</a></b>は、当日の日本株にも影響を与えます。400ドル以上増減があった場合には注意しましょう。</p>
        <p class="card-text">
            <iframe src="//db.225225.jp/bp1.php?fw=200&cs=1"
                    marginwidth=0
                    marginheight=0
                    frameborder=0
                    scrolling=no
                    width=100%
                    height=223>
                <a href=https://ch225.com>ch225世界の株価</a>
            </iframe>
        </p>
    </div>
</div>

<h4 class="d-flex justify-content-between align-items-center mt-3 mb-1">
    <span>広告</span>
</h4>
<div>
    <?php $this->partial("partial/banner_right")?>
</div>
