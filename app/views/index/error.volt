<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>AIエラーの内訳</span>
</h4>

<div class="rounded border bg-white p-3 mb-3">

    <div class="row">
        <div class="col-md-6 mt-3 mb-1">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                    <b>
                        <?php echo $errors['prediction_ymd']?> のAI予想エラー<br />
                        (<?php echo $errors['prediction_error_count']?> 件の内訳)
                    </b>
                </li>
                <?php foreach($errors['prediction_error'] as $errorNo => $errorCnt){?>
                <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                    <span><?php echo ListedCompanies::$aiErrorMessages[$errorNo]?></span>
                    <strong><?php echo $errorCnt?> 件</strong>
                </li>
                <?php }?>
            </ul>
        </div>
        <div class="col-md-6 mt-3 mb-1">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                    <b>
                        <?php echo $errors['training_ymd']?> のAI訓練エラー<br />
                        (<?php echo $errors['training_error_count']?> 件の内訳)
                    </b>
                </li>
                <?php foreach($errors['training_error'] as $errorNo => $errorCnt){?>
                <li class="list-group-item d-flex justify-content-between lh-condensed outer-frame">
                    <span><?php echo ListedCompanies::$aiErrorMessages[$errorNo]?></span>
                    <strong><?php echo $errorCnt?> 件</strong>
                </li>
                <?php }?>
            </ul>
        </div>
    </div>

</div>

<div class="mb-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>


