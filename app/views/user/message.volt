<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>メッセージ</span>
</h4>
<div class="rounded border bg-white p-3 mb-3">
    <div class="row">
        <div class="col-md-6 mt-3 mb-1">
            <div class="alert alert-warning" role="alert">
            <?php if($action){?>
                デバッグ
            <?php }else{?>
                <?php echo $message?>
            <?php }?>
            </div>
            <div class="mt-4">
                &gt;&gt;&nbsp;<a href="/" title="トップへ戻る">トップへ戻る</a>
            </div>
        </div>

        <div class="col-md-6 mt-3 mb-1">
            <?php if (strstr($_SERVER['HTTP_HOST'], 'localhost')) {?>
                <div style="background:#EFEFEF;">広告</div>
            <?php } else { ?>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- AIの株価予想【right】 -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-6074202716971264"
                 data-ad-slot="7128011710"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
            <?php } ?>
        </div>
    </div>
</div>
