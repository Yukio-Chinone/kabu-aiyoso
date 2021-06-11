<div>
    <div class="row">
        <div class="col-md-6 mb-2">
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
        <div class="col-md-6 mb-2">
            <input type="text" name="stock_code" id="stock_code" class="form-control" placeholder="銘柄コード or 企業名" value="<?php echo $stockCode?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="market_no" id="market_no" class="custom-select d-block w-100">
                <option value="">東証1部 / 東証2部 / マザーズ</option>
                <option value="3"<?php echo (3==$marketNo)?' selected':''?>>東証1部</option>
                <option value="4"<?php echo (4==$marketNo)?' selected':''?>>東証2部</option>
                <option value="5"<?php echo (5==$marketNo)?' selected':''?>>マザーズ</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="cost" id="cost" class="custom-select d-block w-100" required>
                <option value="9999999999"<?php echo (9999999999==$cost)?' selected':''?>>購入可能額 (未指定)</option>
                <option value="100000"<?php echo (100000==$cost)?' selected':''?>>10万円以下で購入可</option>
                <option value="150000"<?php echo (150000==$cost)?' selected':''?>>15万円以下で購入可</option>
                <option value="200000"<?php echo (200000==$cost)?' selected':''?>>20万円以下で購入可</option>
                <option value="250000"<?php echo (250000==$cost)?' selected':''?>>25万円以下で購入可</option>
                <option value="300000"<?php echo (300000==$cost)?' selected':''?>>30万円以下で購入可</option>
                <option value="350000"<?php echo (350000==$cost)?' selected':''?>>35万円以下で購入可</option>
                <option value="400000"<?php echo (400000==$cost)?' selected':''?>>40万円以下で購入可</option>
                <option value="450000"<?php echo (450000==$cost)?' selected':''?>>45万円以下で購入可</option>
                <option value="500000"<?php echo (500000==$cost)?' selected':''?>>50万円以下で購入可</option>
                <option value="550000"<?php echo (550000==$cost)?' selected':''?>>55万円以下で購入可</option>
                <option value="600000"<?php echo (600000==$cost)?' selected':''?>>60万円以下で購入可</option>
                <option value="650000"<?php echo (650000==$cost)?' selected':''?>>65万円以下で購入可</option>
                <option value="700000"<?php echo (700000==$cost)?' selected':''?>>70万円以下で購入可</option>
                <option value="750000"<?php echo (750000==$cost)?' selected':''?>>75万円以下で購入可</option>
                <option value="800000"<?php echo (800000==$cost)?' selected':''?>>80万円以下で購入可</option>
                <option value="850000"<?php echo (850000==$cost)?' selected':''?>>85万円以下で購入可</option>
                <option value="900000"<?php echo (900000==$cost)?' selected':''?>>90万円以下で購入可</option>
                <option value="950000"<?php echo (950000==$cost)?' selected':''?>>95万円以下で購入可</option>
                <option value="1000000"<?php echo (1000000==$cost)?' selected':''?>>100万円以下で購入可</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="correct_rate" id="correct_rate" class="custom-select d-block w-100" required>
                <option value="0">学習済AIの正解率 (未指定)</option>
                <?php $opval = 99;?>
                <?php for($i = 0; $i <100; $i++){?>
                <option value="<?php echo $opval?>"<?php echo ($correctRate==$opval)?' selected':''?>><?php echo $opval?>%以上の正解率を持つAI</option>
                <?php
                if($opval <= 90){
                    $opval -= 20;
                    if($opval <= 0) break;
                }else{
                    $opval --;
                }
                ?>
                <?php }?>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="predicted_rate" id="predicted_rate" class="custom-select d-block w-100">
                <option value="">株価と予想のズレ率(未指定)</option>
                <?php $opval = 1;?>
                <?php for($i = 1; $i <= 100; $i++){?>
                <option value="<?php echo $opval?>"<?php echo ($predictedRate==$opval)?' selected':''?>>過去10日の株価と予想ズレが<?php echo $opval?>%未満</option>
                <?php
                if($opval >= 10){
                    $opval += 10;
                    if($opval > 100) break;
                }else{
                    $opval ++;
                }
                ?>
                <?php }?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="updown" id="updown" class="custom-select d-block w-100">
                <option value="">上昇/下降予想 (未指定)</option>
                <option value=">="<?php echo ($updown==">=")?' selected':''?>>上昇予想のみ</option>
                <option value="<="<?php echo ($updown=="<=")?' selected':''?>>下降予想のみ</option>
            </select>
            <input type="hidden" name="rate_of_increase" value="1" />
        </div>
        <div class="col-md-6 mb-2">
            <select name="around_line" id="around_line" class="custom-select d-block w-100">
                <option value="">株価との位置 (未指定)</option>
                <optgroup label="抵抗線">
                    <option value="5"<?php echo ($aroundLine=="5")?' selected':''?>>抵抗線より上 (レンジ外)</option>
                    <option value="3"<?php echo ($aroundLine=="3")?' selected':''?>>抵抗線付近 (レンジ外)</option>
                    <option value="1"<?php echo ($aroundLine=="1")?' selected':''?>>抵抗線付近 (レンジ内)</option>
                </optgroup>
                <optgroup label="支持線">
                    <option value="2"<?php echo ($aroundLine=="2")?' selected':''?>>支持線付近 (レンジ内)</option>
                    <option value="4"<?php echo ($aroundLine=="4")?' selected':''?>>支持線付近 (レンジ外)</option>
                    <option value="6"<?php echo ($aroundLine=="6")?' selected':''?>>支持線より下 (レンジ外)</option>
                </optgroup>
                <optgroup label="移動平均線">
                    <option value="moveAroundUp"<?php echo ($aroundLine=="moveAroundUp")?' selected':''?>>移動平均付近 (上)</option>
                    <option value="moveAroundDn"<?php echo ($aroundLine=="moveAroundDn")?' selected':''?>>移動平均付近 (下)</option>
                </optgroup>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="rsi" id="rsi" class="custom-select d-block w-100">
                <option value="">RSI (未指定)</option>
                <option value="50"<?php echo ($rsi=="50")?' selected':''?>>RSI:31〜69% (通常)</option>
                <option value="30"<?php echo ($rsi=="30")?' selected':''?>>RSI:30%以下 (売られすぎ)</option>
                <option value="70"<?php echo ($rsi=="70")?' selected':''?>>RSI:70%以上 (買われすぎ)</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
        </div>
    </div>

    <?php if($optionArea == "open"){?>
    <div id="option" style="display:block;">
    <?php } else {?>
    <div id="option" style="display:none;">
    <?php }?>

    <hr />

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="resistant_line" id="resistant_line" class="custom-select d-block w-100">
                <option value="">抵抗線/支持線の跨ぎ (未指定)</option>
                <optgroup label="抵抗線">
                    <option value="1"<?php echo ($resistantLine=="1")?' selected':''?>>株価が抵抗線を下↗上へ跨いだ</option>
                    <option value="3"<?php echo ($resistantLine=="3")?' selected':''?>>株価が抵抗線を上↘下へ跨いだ</option>
                </optgroup>
                <optgroup label="支持線">
                    <option value="2"<?php echo ($resistantLine=="2")?' selected':''?>>株価が支持線を上↘下へ跨いだ</option>
                    <option value="4"<?php echo ($resistantLine=="4")?' selected':''?>>株価が支持線を下↗上へ跨いだ</option>
                </optgroup>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="straddle_line" id="straddle_line" class="custom-select d-block w-100">
                <option value="">移動平均線の跨ぎ (未指定)</option>
                <optgroup label="↗上へ跨いだ">
                    <option value="moveUp"<?php echo ($straddleLine=="moveUp")?' selected':''?>>移動平均を下↗上へ跨いだ</option>
                    <option value="25dUp"<?php echo ($straddleLine=="25dUp")?' selected':''?>>25日移動平均を下↗上へ跨いだ</option>
                    <option value="75dUp"<?php echo ($straddleLine=="75dUp")?' selected':''?>>75日移動平均を下↗上へ跨いだ</option>
                    <option value="13wUp"<?php echo ($straddleLine=="13wUp")?' selected':''?>>13週移動平均を下↗上へ跨いだ</option>
                    <option value="26wUp"<?php echo ($straddleLine=="26wUp")?' selected':''?>>26週移動平均を下↗上へ跨いだ</option>
                    <option value="52wUp"<?php echo ($straddleLine=="52wUp")?' selected':''?>>52週移動平均を下↗上へ跨いだ</option>
                </optgroup>
                <optgroup label="↘上へ跨いだ">
                    <option value="moveDn"<?php echo ($straddleLine=="moveDn")?' selected':''?>>移動平均を上↘下へ跨いだ</option>
                    <option value="25dDn"<?php echo ($straddleLine=="25dDn")?' selected':''?>>25日移動平均を上↘下へ跨いだ</option>
                    <option value="75dDn"<?php echo ($straddleLine=="75dDn")?' selected':''?>>75日移動平均を上↘下へ跨いだ</option>
                    <option value="13wDn"<?php echo ($straddleLine=="13wDn")?' selected':''?>>13週移動平均を上↘下へ跨いだ</option>
                    <option value="26wDn"<?php echo ($straddleLine=="26wDn")?' selected':''?>>26週移動平均を上↘下へ跨いだ</option>
                    <option value="52wDn"<?php echo ($straddleLine=="52wDn")?' selected':''?>>52週移動平均を上↘下へ跨いだ</option>
                </optgroup>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="sma25d_up" id="sma25d_up" class="custom-select d-block w-100">
                <option value="">25日移動平均の向き (未指定)</option>
                <option value="1"<?php echo ($sma25dUp=="1")?' selected':''?>>25日移動平均が上向き↗(上昇)</option>
                <option value="0"<?php echo ($sma25dUp=="0")?' selected':''?>>25日移動平均が下向き↘(下降)</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="sma75d_up" id="sma75d_up" class="custom-select d-block w-100">
                <option value="">75日移動平均の向き (未指定)</option>
                <option value="1"<?php echo ($sma75dUp=="1")?' selected':''?>>75日移動平均が上向き↗(上昇)</option>
                <option value="0"<?php echo ($sma75dUp=="0")?' selected':''?>>75日移動平均が下向き↘(下降)</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="bollinger_p2_up" id="bollinger_p2_up" class="custom-select d-block w-100">
                <option value="">BollingerBand の向き (未指定)</option>
                <option value="1"<?php echo ($bollingerP2Up=="1")?' selected':''?>>BollingerBand が上向き↗</option>
                <option value="0"<?php echo ($bollingerP2Up=="0")?' selected':''?>>BollingerBand が下向き↘</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="bb_position" id="bb_position" class="custom-select d-block w-100">
                <option value="">BollingerBand と 株価 (未指定)</option>
                <optgroup label="+">
                    <option value="+3"<?php echo ($bbPosition=="+3")?' selected':''?>>株価が+3σ以上</option>
                    <option value="+2"<?php echo ($bbPosition=="+2")?' selected':''?>>株価が+2σ以上</option>
                    <option value="+2Dn"<?php echo ($bbPosition=="+2Dn")?' selected':''?>>株価が+2σを上↘下に跨いだ</option>
                </optbroup>
                    <optgroup label="-">
                    <option value="-2Up"<?php echo ($bbPosition=="-2Up")?' selected':''?>>株価が-2σを下↗上に跨いだ</option>
                    <option value="-2"<?php echo ($bbPosition=="-2")?' selected':''?>>株価が-2σ以下</option>
                    <option value="-3"<?php echo ($bbPosition=="-3")?' selected':''?>>株価が-3σ以下</option>
                </optgroup>
            </select>
        </div>
    </div>

    <hr />
    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="up_beard" id="up_beard" class="custom-select d-block w-100">
                <option value="">上髭のチェック (未指定)</option>
                <option value="1"<?php echo ($upBeard=="1")?' selected':''?>>上髭が足より短い(売り圧力=弱)</option>
                <option value="2"<?php echo ($upBeard=="2")?' selected':''?>>上髭が足より長い(売り圧力=強)</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <select name="down_beard" id="down_beard" class="custom-select d-block w-100">
                <option value="">下髭のチェック (未指定)</option>
                <option value="1"<?php echo ($downBeard=="1")?' selected':''?>>下髭が足より短い(買い圧力=弱)</option>
                <option value="2"<?php echo ($downBeard=="2")?' selected':''?>>下髭が足より長い(買い圧力=強)</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-2">
            <select name="crossfoot" id="crossfoot" class="custom-select d-block w-100">
                <option value="">十字足[気迷い] (未指定)</option>
                <option value="1"<?php echo ($crossfoot=="1")?' selected':''?>>十字足[気迷い] を含めない</option>
            </select>
        </div>
        <div class="col-md-6 mb-2">
            <input type="text" name="profit_value" id="profit_value" class="form-control" placeholder="利益金額 (未指定)" value="<?php echo $profitValue?>">
        </div>
    </div>

    </div>

    <p class="option">
        <a href="javascript:void(0)" onclick="optionArea('option')">&gt;&gt;&nbsp;オプション</a>
        <input type="hidden" name="option_area" value="<?php echo $optionArea?>">
    </p>

    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="float2cloumn">
                <input id="order_predicted_rate" type="checkbox" name="order_predicted_rate" value="1"<?php echo ($orderPredictedRate=="1")?' checked':''?>>
                <label for="order_predicted_rate">予想のズレ率が低い順</label>
            </div>
            <div class="float2cloumn">
                <input id="order_correct_rate" type="checkbox" name="order_correct_rate" value="1"<?php echo ($orderCorrectRate=="1")?' checked':''?>>
                <label for="order_correct_rate">AIの正解率が高い順</label>
            </div>
            <div class="floatclear"><hr /></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="float2cloumn">
                <input onclick="offCheckbox('rate_of_increase_dn')" id="rate_of_increase_up" type="checkbox" name="rate_of_increase_up" value="1"<?php echo ($rateOfIncreaseUp=="1")?' checked':''?>>
                <label for="rate_of_increase_up">予想利益の大きい順</label>
            </div>
            <div class="float2cloumn">
                <input onclick="offCheckbox('rate_of_increase_up')" id="rate_of_increase_dn" type="checkbox" name="rate_of_increase_dn" value="1"<?php echo ($rateOfIncreaseDn=="1")?' checked':''?>>
                <label for="rate_of_increase_dn">予想利益の小さい順</label>
            </div>
            <div class="floatclear"><hr /></div>
        </div>
    </div>


</div>
