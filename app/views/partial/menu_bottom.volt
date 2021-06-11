
<div class="mb-3 mt-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>

<?php if(isset($rankingLimit)){?>
<div class="row">

    <div class="col-md-12 mt-3 mb-1">
        <h4 class="d-flex justify-content-start align-items-center">
            銘柄の検索ランキング（週間トップ<?php echo $rankingLimit?>）
        </h4>
        <ul class="list-group">
            <?php foreach($searchRanking as $ranking){?>
                <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <?php $listedCompany = ListedCompanies::findByStockCode($ranking["stock_code"]);?>
                <a href="/index/detail/<?php echo $listedCompany->getStockCode()?>" title="<?php echo $listedCompany->getStockCode()?> : <?php echo $listedCompany->getName()?>">
                    <?php echo $listedCompany->getStockCode()?> : <?php echo $listedCompany->getName()?> ..... <?php echo $ranking["count"]?>回
                </a>
                </li>
            <?php }?>
        </ul>
    </div>

</div>
<?php }?>

<div class="row">

    <div class="col-md-6 mt-3 mb-1">
        <h4 class="d-flex justify-content-start align-items-center mb-3">
            学習済AI による予想状況
        </h4>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <b>学習済AIを使って「<?php echo $predictionInfos['prediction_ymd']?>」のデータから今後の株価を予想中。</b>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame comment">
                予測処理は「平日の17:00〜19:00（約2時間）」の間に実施しています。
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>予想対象の銘柄</span>
                <strong><?php echo number_format($predictionInfos['prediction_target'])?> 件</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>予想待ちの銘柄</span>
                <strong><?php echo number_format($predictionInfos['prediction_waiting'])?> 件</strong>
            </li>
            <!--
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>予想途中の銘柄</span>
                <strong><?php echo number_format($predictionInfos['middle'])?> 件</strong>
            </li>
            -->
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>予測失敗の銘柄</span>
                <strong>
                    <a href="/index/error">
                        <?php echo number_format($predictionInfos['prediction_middle_error'])?> 件
                    </a>
                </strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>予測完了の銘柄</span>
                <strong><?php echo number_format($predictionInfos['prediction_complete'])?> 件</strong>
            </li>

        </ul>
    </div>

    <div class="col-md-6 mt-3 mb-1">
        <h4 class="d-flex justify-content-start align-items-center mb-3">
            AIの最新学習状況
        </h4>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <b>AIは「<?php echo $trainingInfos['training_ymd']?>」の株価データを使って学習中。</b>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame comment">
                AIは定期的に最新銘柄を使って株価の流れを学習をしています。
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習対象のAI</span>
                <strong><?php echo number_format($trainingInfos['training_target'])?> 個</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習待ちのAI</span>
                <strong><?php echo number_format($trainingInfos['training_waiting'])?> 個</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習途中のAI</span>
                <strong><?php echo number_format($trainingInfos['training_middle'] - $trainingInfos['training_middle_error'])?> 個</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習失敗のAI</span>
                <a href="/index/error">
                    <strong><?php echo number_format($trainingInfos['training_middle_error'])?> 個</strong>
                </a>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習完了のAI (更新あり)</span>
                <strong><?php echo number_format($trainingInfos['training_complete'])?> 個</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                <span>学習完了のAI (更新なし)</span>
                <strong><?php echo number_format($trainingInfos['training_complete_noupdate'])?> 個</strong>
            </li>
        </ul>
    </div>

</div>

<div class="mb-3 mt-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>

<hr class="mb-4">