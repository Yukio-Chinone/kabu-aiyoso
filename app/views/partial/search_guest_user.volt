<div>
    <div class="row">
        <div class="col-md-12 mb-2">
            <select name="ymd" id="ymd" class="custom-select d-block w-100">
                <?php foreach($currentYmds as $currentYmd){?>
                <?php
                $compMessage = "済";
                $prediction_status = "";
                if($currentYmd == $predictionInfos['prediction_ymd']){
                    $denominator = $predictionInfos['prediction_target'];
                    $numerator = $predictionInfos['prediction_complete'];
                    $prediction_status = "($numerator/$denominator)";
                    if($predictionInfos['prediction_waiting'] > 0){
                        $compMessage = "中";
                    }
                }
                $spYmd = explode("-",$currentYmd);
                $dspMd = $spYmd[1]. "/". $spYmd[2];
                ?>
                <option value="<?php echo $currentYmd?>"<?php echo ($ymd==$currentYmd)?' selected':''?>>
                <?php echo $dspMd?> 予想<?php echo $compMessage?> <?php echo $prediction_status?>
                </option>
                <?php }?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mb-2">
            <input type="text"
                   name="stock_code"
                   id="stock_code"
                   class="form-control"
                   placeholder="銘柄コード or 企業名"
                   value="<?php echo $stockCode?>"
                   required>
        </div>
    </div>
    <input type="hidden" name="cost" value="9999999999" />
</div>