<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>銘柄検索</span>
</h4>
<div class="rounded border bg-white p-3 mb-3">

    <form name="searchForm" class="needs-validation" novalidate action="/#searchTop" method="get">

        <?php $this->partial("partial/search_paid_user")?>
        <div class="text-muted">検索条件はCookieに保存されます(予想日は除く)</div>
        <div class="row">
            <div class="col-md-12 mt-2">
                <button name="button" value="submit"
                        class="btn btn-primary btn-lg w-49 search_btn_right"
                        type="submit">銘柄検索
                </button>
                <button name="button" value="reset"
                        class="btn btn-secondary btn-lg w-49 search_btn_left"
                        type="button"
                        onclick="jumpUrl('/?button=reset'); return false;">リセット
                </button>
            </div>

        </div>
        <input type="hidden" name="offset" = value="0">
        <input type="hidden" name="form_time" value="<?php echo time()?>" />
    </form>
</div>

<?php if($button === "submit" || $topChart === true){?>

    <?php if($topChart){?>
    <?php $this->partial("partial/banner_content")?>
    <?php }?>

<h4 id="searchTop" class="d-flex justify-content-start align-items-center mt-4 mb-1">
    <?php if($topChart){?>
    <span class="today_chart">今日の上昇予想チャート</span>
    <?php }else{ ?>
    <span><?php echo $subTitle?></span>&nbsp;
    <span class="badge badge-secondary badge-pill"><?php echo number_format($searchResultCount)?></span>
    <?php }?>
</h4>
<?php if($topChart){?>
<?php }?>
<div class="rounded border bg-white p-3 mb-3 ">
    <!-- ページネーション -->
    <div>
        <nav aria-label="...">
            <ul class="pagination">
                <!-- 前へ -->
                <?php if(empty($prevQueryString)){?>
                    <li class="page-item disabled">
                        <span class="page-link">前へ</span>
                    </li>
                <?php }else{?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo $prevQueryString?>">前へ</a>
                    </li>
                <?php }?>

                <li class="page-item disabled">
                    <span class="page-link"><?php echo $startOffset?> 〜 <?php echo $endOffset?></span>
                </li>

                <!-- 次へ -->
                <?php if(empty($nextQueryString)){?>
                <li class="page-item disabled">
                    <span class="page-link">次へ</span>
                </li>
                <?php }else{?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $nextQueryString?>">次へ</a>
                </li>
                <?php }?>
            </ul>
        </nav>
    </div>

    <!-- 銘柄一覧 -->
    <ul class="list-inline">

        <!-- 銘柄分析エラー -->
        <?php if(!empty($stockError)){?>
        <p><?php echo $stockError?></p>
        <?php }?>

        <?php if(!empty($searchResults) && empty($stockError)){?>
        <?php foreach($searchResults as $searchResult){ // ########## 銘柄数分ループ ########## ?>

        <?php
        $stockCode = $searchResult->getStockCode();
        $stockName = $searchResult->getName();
        $closeValue = $searchResult->getClose();
        ?>

        <!-- 銘柄詳細 -->
        <?php $stockFullName = $stockCode. ": ". $stockName;?>
        <h3 id="stock<?php echo $stockCode?>">
            <a href="/index/detail/<?php echo $stockCode?>#searchTop"
               title="<?php echo $stockFullName;?>"><?php echo $stockFullName;?></a>&nbsp;
            <?php if($stockCode != Stock::$nikkei225){?>
            <span class="inddustry"><?php echo Stock::$searchMarket[$searchResult->getMarketNo()]?>
                / <?php echo Stock::$industries[$searchResult->getIndustryId()]?></span>&nbsp;
            <?php }?>
            <br />
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
        </h3>

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
        ?>
        <div class="btn-group btn-group-sm btn_favourite mb-4" role="group" aria-label="お気に入り">
            <button type="button" id="favorite_<?php echo $stockCode?>_1" class="<?php echo $btnCss[1]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $searchResult->getStockCode()?>, 1, '<?php echo $searchResult->getYmd();?>');">(買)保有中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_2"  class="<?php echo $btnCss[2]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $searchResult->getStockCode()?>, 2, '<?php echo $searchResult->getYmd();?>');">(買)検討中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_3"  class="<?php echo $btnCss[3]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $searchResult->getStockCode()?>, 3, '<?php echo $searchResult->getYmd();?>');">(売)保有中</button>
            <button type="button" id="favorite_<?php echo $stockCode?>_4"  class="<?php echo $btnCss[4]?>" onclick="getRequest('/bookmark/request', <?php echo $userId;?>, <?php echo $searchResult->getStockCode()?>, 4, '<?php echo $searchResult->getYmd();?>');">(売)検討中</button>
        </div>

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
                    <span><a href="javascript:void(0);" onclick="dspOnOff('addinfo_<?php echo $stockCode?>');">&gt;&gt;&nbsp;移動平均値を表示</a></span>
                </div>
            </div>
        </div>

        <p class="hazure"><b>過去10日×10回の予想ズレ率 (1％以下が安定銘柄、乱高下時ほど高い): </b><br />
        <?php
        $predictedRates = $searchResult->getPredictedRates($stockCode, 10);
        foreach($predictedRates as $predictedRate){
            if (abs($predictedRate) >= 1.0){
                echo "<span class='ng'>".$predictedRate."％</span>";
            } else {
                echo "<span class='ok'>".$predictedRate."％</span>";
            }
        }
        ?>
        </p>

        <!-- ##### 追加情報 ##### -->
        <div id="addinfo_<?php echo $stockCode?>" style="display:none;">
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
            <img width="100%"
                style="border:solid 1px #CCC;"
                class="trim_chart"
                id="img_stock<?php echo $stockCode?>_<?php echo $ymd?>"
                src="<?php echo Stock::getChartUrl($stockCode, $ymd)?>"
                alt="<?php echo $stockFullName;?>"/>

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

            <script>
            <?php
            $imgUrls[$stockCode] = [];
            foreach($predictYmds[$stockCode] as $predictYmd){
                $imgUrls[$stockCode][] = "'" . Stock::getChartUrl($stockCode, $predictYmd) . "'";
            }
            ?>
            var picsSrc_<?php echo $stockCode?> = [<?php echo implode(",", $imgUrls[$stockCode]);?>];
            var picsLen_<?php echo $stockCode?> = picsSrc_<?php echo $stockCode?>.length;
            var num_<?php echo $stockCode?> = <?php echo $predictSkip?>;

            //1日前、5日前
            function go_forward_<?php echo $stockCode?>(day){

                if (num_<?php echo $stockCode?> < picsLen_<?php echo $stockCode?> - day) {
                    num_<?php echo $stockCode?> = num_<?php echo $stockCode?> + day;
                    if (num_<?php echo $stockCode?> >= picsLen_<?php echo $stockCode?>){
                        num_<?php echo $stockCode?> = picsLen_<?php echo $stockCode?> - 1;
                    }
                    //前日ボタン活性
                    document.getElementById("ffbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("fbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("bbbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("bbutton_<?php echo $stockCode?>").disabled = false;
                }
                else{
                    //前日ボタン非活性
                    document.getElementById("ffbutton_<?php echo $stockCode?>").disabled = true;
                    document.getElementById("fbutton_<?php echo $stockCode?>").disabled = true;
                }
                document.getElementById("img_stock<?php echo $stockCode?>_<?php echo $ymd?>").src=picsSrc_<?php echo $stockCode?>[num_<?php echo $stockCode?>];
            }

            //1日後、5日後
            function go_back_<?php echo $stockCode?>(day){

                if (num_<?php echo $stockCode?> > 0) {
                    num_<?php echo $stockCode?> = num_<?php echo $stockCode?> - day;
                    if (num_<?php echo $stockCode?> < 0){
                        num_<?php echo $stockCode?> = 0;
                    }
                    //翌日ボタン活性
                    document.getElementById("bbbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("bbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("ffbutton_<?php echo $stockCode?>").disabled = false;
                    document.getElementById("fbutton_<?php echo $stockCode?>").disabled = false;
                }
                else{
                    //翌日ボタン非活性
                    document.getElementById("bbbutton_<?php echo $stockCode?>").disabled = true;
                    document.getElementById("bbutton_<?php echo $stockCode?>").disabled = true;
                }
                document.getElementById("img_stock<?php echo $stockCode?>_<?php echo $ymd?>").src=picsSrc_<?php echo $stockCode?>[num_<?php echo $stockCode?>];
            }
            </script>

            <div class="day_buttons">
                <input id="ffbutton_<?php echo $stockCode?>" class="btn" type="button" value="<< 5日前" onclick="go_forward_<?php echo $stockCode?>(5)">
                <input id="fbutton_<?php echo $stockCode?>" class="btn" type="button" value="< 1日前" onclick="go_forward_<?php echo $stockCode?>(1)">
                &nbsp;&nbsp;
                <input id="bbutton_<?php echo $stockCode?>"type="button" class="btn" value="1日後 >" onclick="go_back_<?php echo $stockCode?>(1)">
                <input id="bbbutton_<?php echo $stockCode?>"type="button" class="btn" value="5日後 >>" onclick="go_back_<?php echo $stockCode?>(5)">
            </div>
            <a href="javascript:void(0);" class=check_point onclick="dspOnOff('check_point<?php echo $stockCode?>');">
                <img src="/img/check-point-s.png"/>
            </a>

            <a href="javascript:void(0);">
                <p id="imgbtn_stock<?php echo $stockCode?>_<?php echo $ymd?>" onclick="biggerImg(<?php echo $stockCode?>, '<?php echo $ymd?>')">+</p>
            </a>
        </div>

        <div class="short_message" id='check_point<?php echo $stockCode?>' style="display: none;">
            <b class="short_message_title">ポイント1</b><br />
            <b>①移動平均線</b>、<b>②抵抗線</b>、<b>③支持線</b> は株価の下支えや、上昇時の抵抗にもなります。
            しかしその反面、①②③付近では、”投資家の不安心理”により、予想外の動きになる事があります。<br />
            よりリスクを低くする為に、①②が上昇への抵抗になっている場合には、<br />
            - 株価が①②を抜けたら買う。<br />
            - 株価が①②を抜けなければ買わない、保有中なら利益確定。<br />
            (売りの場合には①③で考える)<br />
            などの検討が必要です。<br />
            <br />
            <b class="short_message_title">ポイント2</b><br />
            前日株価に比べて、大きく窓をあけて始まった銘柄や、長いロウソクの銘柄は、外部要因（政治、企業ニュース、事件、事故、災害など）による一時的な上昇/下降の場合があります。<br />
            その場合には、翌日に利益確定売りにより大きく下がることがあるので、様子を見る事も必要です。
        </div>

        <hr />
        <?php }?>
        <?php }?>
    </ul>



    <?php if(count($searchResults) <= 0){?>
    <p class="mb-3">データがありません。</p>
    <?php }?>

    <!-- ページネーション -->
    <div>
        <nav aria-label="...">
            <ul class="pagination">
                <!-- 前へ -->
                <?php if(empty($prevQueryString)){?>
                <li class="page-item disabled">
                    <span class="page-link">前へ</span>
                </li>
                <?php }else{?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $prevQueryString?>">前へ</a>
                </li>
                <?php }?>

                <li class="page-item disabled">
                    <span class="page-link"><?php echo $startOffset?> 〜 <?php echo $endOffset?></span>
                </li>

                <!-- 次へ -->
                <?php if(empty($nextQueryString)){?>
                <li class="page-item disabled">
                    <span class="page-link">次へ</span>
                </li>
                <?php }else{?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $nextQueryString?>">次へ</a>
                </li>
                <?php }?>
            </ul>
        </nav>
    </div>

</div>
<?php }?>

<?php $this->partial("partial/menu_bottom")?>
