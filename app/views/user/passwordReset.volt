<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>パスワードの再設定</span>
</h4>
<div class="rounded border bg-white p-3 mb-3">
    <div class="row">
        <div class="col-md-6 mt-3 mb-1">

            <?php if($error_message != ""){?>
            <p class="center red"><?php echo $error_message?></p>
            <?php }?>

            <form method="post" action="">

            <input type="text"
                   name="password1"
                   id="password1"
                   class="form-control login-form"
                   placeholder="新パスワード"
                   value="<?php echo $password1?>"
                   required>

            <?php if(!empty($password1Error)){?>
            <p class="red"><?php echo $password1Error?></p>
            <?php }?>

            <input type="text"
                   name="password2"
                   id="password2"
                   class="form-control login-form"
                   placeholder="新パスワード(再入力)"
                   value="<?php echo $password2?>"
                   required>

            <?php if(!empty($password2Error)){?>
            <p class="red"><?php echo $password2Error?></p>
            <?php }?>

            <button name="button" value="submit" class="btn btn-primary login-submit" type="submit">
                パスワードを設定
            </button>
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token?>" />
            </form>

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



