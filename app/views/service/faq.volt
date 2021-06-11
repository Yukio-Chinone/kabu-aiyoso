<?php
$qid = 0;
?>

<?php $no = 1?>
<h4 id="searchTop" class="d-flex justify-content-start align-items-center mb-1">
    <span>よくある質問</span>
</h4>
<div class="rounded border bg-white p-3 mb-3 ">

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-2">Q: AIが予測したデータは、どのような投資手法向きですか？</h5>
    <hr />
    <p>
        主にスイングトレードに向いています。
    </p>
    <br />

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-2">Q: プレミアム検索（月額課金）の入会方法と退会方法を教えてください。</h5>
    <hr />
    <p>
        以下のページを参照ください。<br />
        ・<a href="https://manual.infotop.jp/customer/548?css=style" target="_blank">月額課金の入会方法</a><br />
        ・<a href="https://manual.infotop.jp/customer/552?css=style" target="_blank">月額課金の退会方法</a><br />
    </p>

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: AIの予想処理はいつ実施されますか？</h5>
    <hr />
    <p>
        予測処理は「<b>17:00〜19:00（約2時間）</b>」の間に実施しています。基本的に平日なら毎日実施しています。
    </p>

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: AIはどのくらいの頻度で学習しますか？</h5>
    <hr />
    <p>
        AIは1銘柄に1つ存在します。<br />
        1つのAIが学習を終えるのに約15分以上かかる為、2900個近くのAI(=銘柄数)が全て学習を終えるには、約1ヶ月かかります。<br />
        その為、AI(Model)は <b>1ヶ月に一度</b> 更新されるようになっており、AIの学習率も同じタイミングで更新されます。
    </p>

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: AIの正解率はどのように算出していますか？</h5>
    <hr />
    <p>
        AIは過去データの9割を学習用に、1割を評価用に利用しております。<br />
        AIは 9割のデータの値動きを学習した後、未学習分のデータである1割を予想します。<br />
        その <b>予想した結果(=株価)が一致した割合がAIの正解率</b> となります。
    </p>

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: Facebookを使ってログインしていたのですが、別のFacebookアカウントでログインし直したいです。</h5>
    <hr />
    <p>
        以下の手順をお試しください。
    </p>
    <div class="list">
        <ol>
            <li>当サイトから <b><a href="/user/logout" title="ログアウト">ログアウト</a></b> します。</li>
            <li>当サイトと連携中のFacebookアカウントを使い、Facebookにログインします。</li>
            <li>Facebookから<b>当サイトとの連携を解除</b>してください（Facebookの利用方法は、Facebookのヘルプページ等をご参考ください）</li>
            <li>ご利用中ブラウザの<b>閲覧履歴</b>と<b>キャッシュ</b>を削除してください。</li>
            <li>本サイトに再度 Facebookでログインしてください。この時に別のFacebookアカウントが入力出来るようになるので、入力してください。</li>
        </ol>
    </div>

    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: メールが届かない場合について</h5>
    <hr />
    <p>
        メールが届かない場合は、下記項目についてご確認ください。<br />
        受信設定をされたご記憶がない場合も、設定されている可能性がありますのでご確認ください。<br />
        <br />
        <b>【携帯・スマートフォンのアドレスをご利用の方】</b><br />
        docomo、au、softbankなど各キャリアのセキュリティ設定のためユーザー受信拒否と認識されているか、お客様が迷惑メール対策等で、ドメイン指定受信を設定されている場合に、メールが正しく届かないことがございます。<br />
        <b>kabu.aiyoso.com</b> ドメインを受信できるように設定してください。<br />
        <br />
        各キャリアのドメイン指定受信手順は下記となります。<br />
        <a href="https://www.nttdocomo.co.jp/info/spam_mail/domain/index.html" target="_blank">・docomo</a><br />
        <a href="https://www.au.com/support/service/mobile/trouble/mail/email/filter/detail/domain/" target="_blank">・au</a><br />
        <a href="https://mb.softbank.jp/mb/support/antispam/settings/indivisual/whiteblack/" target="_blank">・softbank</a><br />
        <br />
        <b>【PCメールアドレスをご利用の方】</b><br />
        お使いのメールサービス、メールソフト、ウィルス対策ソフト等の設定により「迷惑メール」と認識され、メールが届かない場合があります。<br />
         （特にYahoo!メールやHotmailなどのフリーメールをお使いの方）
        その場合は「迷惑メールフォルダー」等をご確認いただくかお使いのサービス、ソフトウェアの設定をご確認ください。
    </p>







    <!--
    <h5 id="faq-<?php echo ++ $qid?>" class="mt-5">Q: 質問</h5>
    <hr />
    <p>
        回答
    </p>
    -->

</div>

<div class="mb-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>
