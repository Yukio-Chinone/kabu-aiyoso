<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>ログイン</span>
</h4>
<div class="rounded border bg-white p-3 mb-3">
    <div class="row">
        <div class="col-md-6 mt-3 mb-1">

            <a id="register" href="/user/register" title="新規登録はこちら">新規登録はこちら</a>

            <form method="post" action="/user/login">

            <input type="text"
                   name="username"
                   id="username"
                   class="form-control login-form"
                   placeholder="メールアドレス"
                   value="<?php echo $username?>"
                   required>

            <?php if(!empty($usernameError)){?>
            <p class="red"><?php echo $usernameError?></p>
            <?php }?>

            <input type="text"
                   name="password"
                   id="password"
                   class="form-control login-form"
                   placeholder="パスワード"
                   value="<?php echo $password?>"
                   required>

            <?php if(!empty($passwordError)){?>
            <p class="red"><?php echo $passwordError?></p>
            <?php }?>

            <button name="button" value="submit" class="btn btn-primary login-submit" type="submit">
                メールアドレスでログイン
            </button>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token?>" />
            </form>

            <p class="center mb40"><a href="/user/reset">パスワードを忘れた方はこちら</a></p>

            <a href="<?php echo htmlspecialchars($fblink); ?>" title="facebookでログイン">
                <img width="100%" src="/img/btn_facebook.png" alt="facebookでログイン"/>
            </a>
            <p class="mt-3 text-center lead">
                ログイン後は自動的にサイトに戻ります。
            </p>
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

<div class="mb-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>



