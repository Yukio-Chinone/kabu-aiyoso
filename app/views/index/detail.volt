<h4 id="searchTop" class="d-flex justify-content-start align-items-center mt-4 mb-1">
    <?php if(!empty($prevLink)){?>
    <a href="<?php echo $prevLink?>">銘柄検索</a>&nbsp;&gt;&nbsp;
    <?php }else{?>
    <a href="/#">銘柄検索</a>&nbsp;&gt;&nbsp;
    <?php }?>
    <span><?php echo $subTitle?></span>
</h4>
<div class="rounded border bg-white p-3 mb-3 ">
    <?php
    $btnCss = [];
    $btnCss[1] = "btn btn-outline-primary";
    $btnCss[2] = "btn btn-outline-primary";
    $btnCss[3] = "btn btn-outline-primary";
    $btnCss[4] = "btn btn-outline-primary";

    if(isset($userBookmarks[$stockCode])){
        $btnCss[1] = ($userBookmarks[$stockCode]["status"] == 1)?  "btn btn-primary" :  "btn btn-outline-primary";
        $btnCss[2] = ($userBookmarks[$stockCode]["status"] == 2)?  "btn btn-primary" :  "btn btn-outline-primary";
        $btnCss[3] = ($userBookmarks[$stockCode]["status"] == 3)?  "btn btn-primary" :  "btn btn-outline-primary";
        $btnCss[4] = ($userBookmarks[$stockCode]["status"] == 4)?  "btn btn-primary" :  "btn btn-outline-primary";
    }

    $checkedAt = isset($userBookmarks[$stockCode]["checked_at"]) ? $userBookmarks[$stockCode]["checked_at"] : "";
    ?>

    <!-- 銘柄分析エラー -->
    <?php if(!empty($stockError)){?>
    <p><?php echo $stockError?></p>
    <?php }?>

    <div class="tab">
        <a class="active" href="/index/detail/<?php echo $stockCode?>#searchTop">チャート</a>
        <a href="/index/tweet/<?php echo $stockCode?>#searchTop">ツイート</a>
    </div>
    <div class="floatclear tab_bottom"><hr /></div>

    <!-- 銘柄一覧 -->
    <ul class="list-inline">
        <?php if(!empty($searchResults) && empty($stockError)){ // start(a) ?>
        <?php $loopCnt = 0;?>
        <?php foreach($searchResults as $searchResult){ // start(b) ########## 銘柄数分ループ ##########?>

                <?php
                $stockName = $searchResult->getName();
                $closeValue = $searchResult->getClose();

                $checkedFlag = "";
                if($checkedAt == $searchResult->getYmd()){
                    $checkedFlag = " checked";
                }
                ?>

        <!-- 銘柄詳細 -->
        <?php if($loopCnt == 0){ // start(c) ?>

                <?php
                $latestYmd = $searchResult->getYmd();
                $ymd = $searchResult->getYmd();
                $recentClose = $closeValue;
                $stockFullName = $stockCode. ": ". $stockName;
                ?>

        <h3 id="ymd<?php echo $searchResult->getYmd();?>">
            <?php echo $stockFullName;?>&nbsp;
            <?php if($stockCode != Stock::$nikkei225){?>
            <span class="inddustry"><?php echo Stock::$searchMarket[$searchResult->getMarketNo()]?>
                / <?php echo Stock::$industries[$searchResult->getIndustryId()]?>
            </span>
            <?php }?>
            <br />
            <?php if(empty($userId)){?>
            <span class="predicted_ymd">
                <div class="mt-2 mb-3">
                    予想日: <?php echo $searchResult->getYmd();?>
                    <?php
                    $marketNoCode = Stock::$marketType[$searchResult->getMarketNo()][2];
                    $ylink = sprintf(Stock::YAHOO_FINANCE, $stockCode, $marketNoCode);
                    ?>
                    <a href="<?php echo $ylink?>" target="_blank">[&nbsp;Yahoo finance&nbsp;]</a>&nbsp;
                    <a href="https://kabdoki.com/chart_page/index/<?php echo $stockCode?>" target="_blank">[&nbsp;株の買い時.com&nbsp;]</a>
                </div>
            </span>
            <?php }?>
        </h3>
        <div class="btn-group btn-group-sm btn_favourite mb-2" role="group" aria-label="お気に入り">
            <button type="button" id="favorite_<?php echo $stockCode?>_1" class="<?php echo $btnCss[1]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $stockCode?>, 1, '<?php echo $searchResult->getYmd();?>');">(買)保有中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_2"  class="<?php echo $btnCss[2]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $stockCode?>, 2, '<?php echo $searchResult->getYmd();?>');">(買)検討中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_3"  class="<?php echo $btnCss[3]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $stockCode?>, 3, '<?php echo $searchResult->getYmd();?>');">(売)保有中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_4"  class="<?php echo $btnCss[4]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $stockCode?>, 4, '<?php echo $searchResult->getYmd();?>');">(売)検討中</button>
        </div>

        <?php } else { // else(c) ?>
                <?php
                $ymd = $searchResult->getYmd();
                ?>

        <p class="d-flex justify-content-start align-items-center mt-1 mb-2" id="ymd<?php echo $searchResult->getYmd();?>">
            <?php if(!empty($prevLink)){?>
            <a href="<?php echo $prevLink?>">&gt;&nbsp;銘柄検索</a>
            <?php }else{?>
            <a href="/#">&gt;&nbsp;銘柄検索</a>
            <?php }?>
        </p>

        <?php } // end(c) ?>

        <h3 class="mb-2">
            <?php if(empty($userId)){?>
            <span class="predicted_ymd">
                <div class="mt-2 mb-3">
                    予想日: <?php echo $searchResult->getYmd();?>
                    <?php
                    $marketNoCode = Stock::$marketType[$searchResult->getMarketNo()][2];
                    $ylink = sprintf(Stock::YAHOO_FINANCE, $stockCode, $marketNoCode);
                    ?>
                    <a href="<?php echo $ylink?>" target="_blank">[&nbsp;Yahoo finance&nbsp;]</a>&nbsp;
                    <a href="https://kabdoki.com/chart_page/index/<?php echo $stockCode?>" target="_blank">[&nbsp;株の買い時.com&nbsp;]</a>
                </div>
            </span>
            <?php }else{?>
            <span class="predicted_ymd">
                <div class="mt-2 mb-3">
                    <input type="radio"
                           class="mt-3"
                           name="checkedAt"
                           id="id-<?php echo $searchResult->getYmd();?>"
                           onclick="getRequestChecked('/bookmark/check', <?php echo $userId;?>, <?php echo $stockCode?>, '<?php echo $searchResult->getYmd();?>');"<?php echo $checkedFlag?>>
                    <label for="id-<?php echo $searchResult->getYmd();?>" class="mb-0">&nbsp;
                        予想日: <?php echo $searchResult->getYmd();?></label>
                    <?php
                    $marketNoCode = Stock::$marketType[$searchResult->getMarketNo()][2];
                    $ylink = sprintf(Stock::YAHOO_FINANCE, $stockCode, $marketNoCode);
                    ?>
                    <a href="<?php echo $ylink?>" target="_blank">[&nbsp;Yahoo finance&nbsp;]</a>&nbsp;
                    <a href="https://kabdoki.com/chart_page/index/<?php echo $stockCode?>" target="_blank">[&nbsp;株の買い時.com&nbsp;]</a>
                </div>
            </span>
            <?php }?>
        </h3>

        <!-- ### 1行目 -->
        <div class="row stock">
            <div class="col-md-6 mt-2">
                <?php if($stockCode != Stock::$nikkei225){?>
                <div class="float2cloumn">
                    <span>終値&nbsp;x&nbsp;単元株数</span><br/>
                    <b><?php echo number_format($closeValue)?>円&nbsp;x&nbsp;<?php echo number_format($searchResult->getShareUnit())?>株</b>
                </div>
                <?php }else{ ?>
                <div class="float2cloumn">
                    <span>終値</span><br/>
                    <b><?php echo number_format($closeValue)?>円</b>
                </div>
                <?php }?>
                <div class="float2cloumn">
                    <?php if($stockCode != Stock::$nikkei225){?>
                    <span>最低購入価格</span><br/>
                    <b><?php echo number_format($searchResult->getCost())?>円</b>
                    <?php }?>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
            <div class="col-md-6 mt-2">
                <!-- ######################### Start
                <?php
                $addValue = intval(($searchResult->getPredictedClose() / 100) * $searchResult->getRateOfIncrease());
                //$addValue = number_format($searchResult->getPredictedClose() - $closeValue);
                $mark = ($addValue > 0) ? "+" : "";
                ?>
                <div class="float2cloumn">
                <span>予想後の価格</span><br/>
                <b><?php echo number_format($searchResult->getPredictedClose())?>円</b>
                ######################### End -->
                <div class="float2cloumn">
                    <span>翌日からの予想 (価格)</span><br/>
                    <b><?php echo $mark?><?php echo round( $searchResult->getRateOfIncrease(), 1)?>% (<?php echo $mark?><?php echo number_format($addValue)?>円)</b>
                </div>
                <div class="float2cloumn">
                    <span>予想に利用したAIの正解率</span><br/>
                    <b style="color:orange;"><?php echo round($searchResult->getCorrectRateWhenPredicted(), 2)?>%</b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
        </div>

        <!-- ### 2行目 -->
        <div class="row stock">

            <?php
            $recentAdd = ListedCompanies::selectRecentClose($stockCode) - $closeValue;
            $maxValue = ListedCompanies::selectMaxMinClose($stockCode, $latestYmd, $ymd, "max");

            $maxAdd = 0;
            if($maxValue > 0){
                $maxAdd = $maxValue - $closeValue;
            }
            $minValue = ListedCompanies::selectMaxMinClose($stockCode, $latestYmd, $ymd, "min");
            $minAdd = 0;
            if($minValue > 0){
                $minAdd = $minValue - $closeValue;
            }
            ?>

            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span>本日終値との比較</span><br/>
                    <?php if( $recentAdd > 0 ){ ?>
                    <b style="color:red">+<?php echo number_format($recentAdd)?>円</b>
                    <?php } else if( $recentAdd < 0 ){ ?>
                    <b style="color:blue"><?php echo number_format($recentAdd)?>円</b>
                    <?php } else {?>
                    <span>---</span>
                    <?php }?>
                    </span>
                </div>
                <div class="float2cloumn">
                    <span>本日までの最大 ﾌﾟﾗｽ終値</span><br/>
                    <?php if($maxAdd > 0){?>
                    <b><?php echo number_format($maxValue)?>円 <b style="color:red;">(+<?php echo number_format($maxAdd)?>円)</b></b>
                    <?php }else{?>
                    <span>---</span>
                    <?php }?>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span>本日までの最大 ﾏｲﾅｽ終値</span><br/>
                    <?php if($minAdd < 0){?>
                    <b><?php echo number_format($minValue)?>円 <b style="color:blue;">(<?php echo number_format($minAdd)?>円)</b></b>
                    <?php }else{?>
                    <span>---</span>
                    <?php }?>
                </div>
                <div class="float2cloumn">
                    <span>過去10日間の予想ズレ率</span><br/>
                    <?php
                    $shearRrate = round(100 - $searchResult->getPredictedRate(), 2);
                    $shearValue = number_format(intval(($closeValue / 100) * $shearRrate));
                    ?>
                    <b style="color:orange;">±<?php echo $shearRrate?>%</b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
        </div>

        <!-- ### 3行目 -->
        <div class="row stock">
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span>抵抗線 (終値との差)</span><br/>
                    <?php
                    if(!empty($searchResult->getResistantVal()) &&
                        $searchResult->getResistantVal() < $searchResult->getBollingerP3()){

                        $diffClose = $searchResult->getResistantVal() - $closeValue;
                        $dspResistantVal = sprintf("%s円 (%s円)", number_format($searchResult->getResistantVal()), number_format($diffClose));
                    }
                    else{
                        $dspResistantVal = "なし";
                    }
                    ?>
                    <b><?php echo $dspResistantVal?></b>
                </div>
                <div class="float2cloumn">
                    <span>支持線 (終値との差)</span><br/>
                    <?php
                    if(!empty($searchResult->getSupportVal()) &&
                        $searchResult->getSupportVal() > $searchResult->getBollingerM3()){
                        $diffClose = $searchResult->getSupportVal() - $closeValue;
                        $dspSupportVal = sprintf("%s円 (%s円)", number_format($searchResult->getSupportVal()), number_format($diffClose));
                    }
                    else{
                        $dspSupportVal = "なし";
                    }
                    ?>
                    <b><?php echo $dspSupportVal?></b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span>RSI</span><br/>
                    <b><?php echo number_format($searchResult->getRsi())?>%</b>
                </div>
                <div class="float2cloumn">
                    <span>AIが学習した株価</span><br/>
                    <?php $targetTrainedYmd = ListedCompanies::getLatestTrainedDate($stockCode, $ymd)?>
                    <b><?php echo $targetTrainedYmd?></b><?php echo (!empty($targetTrainedYmd)) ? "まで" : "<b>-</b>" ?>
                    <br />
                    <span><a href="javascript:void(0);" onclick="dspOnOff('addinfo_<?php echo $loopCnt?>');">&gt;&gt;&nbsp;移動平均値を表示</a></span>
                </div>
            </div>
        </div>


        <!-- ##### 追加情報 ##### -->
        <div id="addinfo_<?php echo $loopCnt?>" style="display:none;">
        <hr />

        <!-- ### 4行目 -->
        <div class="row stock">
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span style="color:rgb(240,123,115);">25日移動平均</span><br/>
                    <b><?php echo number_format($searchResult->getPsma25d())?>円</b>
                </div>
                <div class="float2cloumn">
                    <span style="color:rgb(118,167,104);">75日移動平均</span><br/>
                    <b><?php echo number_format($searchResult->getPsma75d())?>円</b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span style="color:rgb(246,195,117);">13週移動平均</span><br/>
                    <b><?php echo number_format($searchResult->getPsma13w())?>円</b>
                </div>
                <div class="float2cloumn">
                    <span style="color:rgb(181,210,89);">26週移動平均</span><br/>
                    <b><?php echo number_format($searchResult->getPsma26w())?>円</b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
        </div>

        <!-- ### 5行目 -->
        <div class="row stock">
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    <span style="color:rgb(75,173,248);">52週移動平均</span><br/>
                    <b><?php echo number_format($searchResult->getPsma52w())?>円</b>
                </div>
                <div class="float2cloumn">
                    <span>予想に利用したAI</span><br/>
                    <?php $modelVersionPredict = $searchResult->getModelVersionPredict()?>
                    ver <b><?php echo ($modelVersionPredict) ? $modelVersionPredict : "3"; ?></b>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
        </div>
        </div>

        <!-- ### 6行目 -->
        <div class="row stock">
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                </div>
                <div class="float2cloumn">
                </div>
                <div class="floatclear"><hr /></div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="float2cloumn">
                    &nbsp;
                </div>
                <div class="float2cloumn">
                    <span><a href="javascript:void(0);" onclick="getSearchCondition('/index/request', <?php echo $searchResult->getStockCode()?>, '<?php echo $searchResult->getYmd();?>');">&gt;&gt;&nbsp;検索条件の表示</a></span>
                </div>
                <div class="floatclear"><hr /></div>
            </div>
        </div>

        <!-- ##### 検索条件 ##### -->
        <div id="search_<?php echo $ymd?>_<?php echo $stockCode?>" style="display:none;">
        <hr />

        <b><?php echo $ymd?> : この銘柄の検索条件</b>
        <div class="row stock">
            <div class="col-md-12 mt-2">
                <?php
                $conditionLink = "?ymd=". $ymd;
                $conditionLink .= "&cost=9999999999";
                $conditionLink .= "&correct_rate=0";
                $conditionLink .= "&rate_of_increase=1";
                $conditionLink .= "&order_predicted_rate=1";
                $conditionLink .= "&order_correct_rate=1";
                $conditionLink .= "&%s=%s";
                $conditionLink .= "&button=submit";
                $conditionLink .= "&offset=0";
                $conditionLink .= "&form_time=". time();
                $conditionLink .= "&option_area=open";
                $conditionLink .= "#searchTop";
                ?>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_updown_>=" class="search_tag" href="<?php echo sprintf($conditionLink, "updown", urlencode(">="));?>" style="display:none;">上昇予想のみ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_updown_<=" class="search_tag" href="<?php echo sprintf($conditionLink, "updown", urlencode("<="));?>" style="display:none;">下降予想のみ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_5" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("5"));?>" style="display:none;">抵抗線より上 (レンジ外)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_3" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("3"));?>" style="display:none;">抵抗線付近 (レンジ外)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_1" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("1"));?>" style="display:none;">抵抗線付近 (レンジ内)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_2" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("2"));?>" style="display:none;">支持線付近 (レンジ内)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_4" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("4"));?>" style="display:none;">支持線付近 (レンジ外)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_6" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("6"));?>" style="display:none;">支持線より下 (レンジ外)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_moveAroundUp" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("moveAroundUp"));?>" style="display:none;">移動平均付近 (上)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_aroundLine_moveAroundDn" class="search_tag" href="<?php echo sprintf($conditionLink, "around_line", urlencode("moveAroundDn"));?>" style="display:none;">移動平均付近 (下)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_resistantLine_1" class="search_tag" href="<?php echo sprintf($conditionLink, "resistant_line", urlencode("1"));?>" style="display:none;">株価が抵抗線を下↗上へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_resistantLine_3" class="search_tag" href="<?php echo sprintf($conditionLink, "resistant_line", urlencode("3"));?>" style="display:none;">株価が抵抗線を上↘下へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_resistantLine_2" class="search_tag" href="<?php echo sprintf($conditionLink, "resistant_line", urlencode("2"));?>" style="display:none;">株価が支持線を上↘下へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_resistantLine_4" class="search_tag" href="<?php echo sprintf($conditionLink, "resistant_line", urlencode("4"));?>" style="display:none;">株価が支持線を下↗上へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_straddleLine_moveUp" class="search_tag" href="<?php echo sprintf($conditionLink, "straddle_line", urlencode("moveUp"));?>" style="display:none;">移動平均を下↗上へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_straddleLine_moveDn" class="search_tag" href="<?php echo sprintf($conditionLink, "straddle_line", urlencode("moveDn"));?>" style="display:none;">移動平均を上↘下へ跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_sma25dUp_1" class="search_tag" href="<?php echo sprintf($conditionLink, "sma25d_up", urlencode("1"));?>" style="display:none;">25日移動平均が上向き↗(上昇)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_sma25dUp_0" class="search_tag" href="<?php echo sprintf($conditionLink, "sma25d_up", urlencode("0"));?>" style="display:none;">25日移動平均が下向き↘(下降)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_sma75dUp_1" class="search_tag" href="<?php echo sprintf($conditionLink, "sma75d_up", urlencode("1"));?>" style="display:none;">75日移動平均が上向き↗(上昇)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_sma75dUp_0" class="search_tag" href="<?php echo sprintf($conditionLink, "sma75d_up", urlencode("0"));?>" style="display:none;">75日移動平均が下向き↘(下降)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bollingerP2Up_1" class="search_tag" href="<?php echo sprintf($conditionLink, "bollinger_p2_up", urlencode("1"));?>" style="display:none;">BollingerBand が上向き↗</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bollingerP2Up_0" class="search_tag" href="<?php echo sprintf($conditionLink, "bollinger_p2_up", urlencode("0"));?>" style="display:none;">BollingerBand が下向き↘</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_+3" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("+3"));?>" style="display:none;">株価が+3σ以上</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_+2" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("+2"));?>" style="display:none;">株価が+2σ以上</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_+2Dn" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("+2Dn"));?>" style="display:none;">株価が+2σを上↘下に跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_-2Up" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("-2Up"));?>" style="display:none;">株価が-2σを下↗上に跨いだ</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_-2" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("-2"));?>" style="display:none;">株価が-2σ以下</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_bbPosition_-3" class="search_tag" href="<?php echo sprintf($conditionLink, "bb_position", urlencode("-3"));?>" style="display:none;">株価が-3σ以下</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_upBeard_1" class="search_tag" href="<?php echo sprintf($conditionLink, "up_beard", urlencode("1"));?>" style="display:none;">上髭が足より短い(売り圧力=弱)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_upBeard_2" class="search_tag" href="<?php echo sprintf($conditionLink, "up_beard", urlencode("2"));?>" style="display:none;">上髭が足より長い(売り圧力=強)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_downBeard_1" class="search_tag" href="<?php echo sprintf($conditionLink, "down_beard", urlencode("1"));?>" style="display:none;">下髭が足より短い(買い圧力=弱)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_downBeard_2" class="search_tag" href="<?php echo sprintf($conditionLink, "down_beard", urlencode("2"));?>" style="display:none;">下髭が足より長い(買い圧力=強)</a>
                <a id="search_<?php echo $ymd?>_<?php echo $stockCode?>_crossfoot_1" class="search_tag" href="<?php echo sprintf($conditionLink, "crossfoot", urlencode("1"));?>" style="display:none;">十字足[気迷い]を含めない</a>
            </div>
        </div>
        </div>

        <!-- ### チャート図 -->
        <div class="chart_area">

            <div style="text-align:center;">
                <a href="javascript:void(0);" onclick='openClose("img_stock<?php echo $stockCode?>_<?php echo $ymd?>", "img_yahoo_stock<?php echo $stockCode?>_<?php echo $ymd?>");'>予想チャート</a>
                <span style="color:#CCC">&nbsp;|&nbsp;</span>
                <a href="javascript:void(0);" onclick='openClose("img_yahoo_stock<?php echo $stockCode?>_<?php echo $ymd?>", "img_stock<?php echo $stockCode?>_<?php echo $ymd?>");'>リアルタイムチャート(本日)</a>
            </div>
            <a href="<?php echo Stock::getChartUrl($stockCode, $searchResult->getYmd())?>" target="_blank">
                <img width="100%"
                    style="border:solid 1px #CCC;"
                    class="trim_chart"
                    id="img_stock<?php echo $stockCode?>_<?php echo $ymd?>"
                    src="<?php echo Stock::getChartUrl($stockCode, $searchResult->getYmd())?>"
                    alt="<?php echo $stockFullName;?>"/>

            </a>
            <div style="padding:0px 9%;
                display:none;
                border:solid 1px #CCC;"
                id="img_yahoo_stock<?php echo $stockCode?>_<?php echo $ymd?>">
                <?php if($stockCode != Stock::$nikkei225){?>
                <img width="100%"src="https://chart.yahoo.co.jp/?code=<?php echo $stockCode?>.T&tm=5d" />
                <?php }else{?>
                <img width="100%"src="https://chart.yahoo.co.jp/?code=<?php echo $stockCode?>.O&tm=5d" />
                <?php }?>
            </div>

            <a href="javascript:void(0);">
                <p id="imgbtn_stock<?php echo $stockCode?>_<?php echo $ymd?>" onclick="biggerImg(<?php echo $stockCode?>, '<?php echo $ymd?>')">+</p>
            </a>
        </div>

        <hr />
        <?php $loopCnt++;?>
        <?php } // end(b) ?>
        <?php } // end(a) ?>
    </ul>

</div>

<?php $this->partial("partial/menu_bottom")?>
