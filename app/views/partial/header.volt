<div class="py-2 text-center">
    <div class="text-right">
        <ul class="list-inline">
            <li class="list-inline-item">
                <a href="/" title="検索">検索</a>
            </li>
            <li class="list-inline-item">
                <a href="/index/stocklist" title="一覧">一覧</a>
            </li>
            <li class="list-inline-item">
                <a href="/service/spec" title="仕様">仕様</a>
            </li>
            <?php if(isset($logined) && $logined === true){?>
                <li class="list-inline-item">
                    <a href="/bookmark" title="お気入り">お気入り</a>
                </li>
                <li class="list-inline-item">
                    <a href="/user"title="<?php echo $userName?>さん"><?php echo htmlspecialchars($userName)?>さん</a>
                </li>
            <?php }else{?>
                <li class="list-inline-item">
                    <a href="/user/login"title="ログイン">ログイン</a>
                </li>
                <li class="list-inline-item">
                    <a href="/user/register"title="新規登録">新規登録</a>
                </li>
            <?php }?>
        </ul>
    </div>
    <h1 class="site-title">
        <a href="/" title="AIの株価予想">
            <img class="logo" src="/img/logo.png" title="AIの株価予想" />&nbsp;AIの株価予想
        </a>
    </h1>
    <h2 class="lead">
        AIを使って直近10日間の株価を予測します。<br />
        毎日<b>15時半〜19時</b>にデータを更新中 !
    </h2>
</div>