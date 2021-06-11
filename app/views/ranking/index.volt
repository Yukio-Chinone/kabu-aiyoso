
<h4 id="searchTop" class="d-flex justify-content-start align-items-center mb-1">
    <span>お気に入りランキング</span>
</h4>
<div class="rounded border bg-white p-3 mb-3 ">

    <!-- サブメニュー -->
    <div class="text-left">
        <ul class="list-inline">
            <li class="list-inline-item">
                <?php if($status == 1){?>
                <b>買い銘柄</b>
                <?php }else{?>
                <a href="/ranking/index/1" title="買い銘柄">買い銘柄</a>
                <?php }?>
            </li>
            <li class="list-inline-item">
                <?php if($status == 2){?>
                <b>売り銘柄</b>
                <?php }else{?>
                <a href="/ranking/index/2" title="売り銘柄">売り銘柄</a>
                <?php }?>
            </li>
        </ul>
    </div>

    <div class="text-left mb-4">
        <?php if($status == 1){?>
            <b>(買い)保有中</b> と <b>(買い)検討中</b> の銘柄ランキングです。
        <?php }?>
        <?php if($status == 2){?>
            <b>(売り)保有中</b> と <b>(売り)検討中</b> の銘柄ランキングです。
        <?php }?>

    </div>

    <!-- お気に入り銘柄一覧 -->
    <table id="rankinglist" width="100%">
        <tr>
            <th width="16%">銘柄コード</th>
            <th width="6%">別</th>
            <th>企業名</th>
            <th width="25%">マーケット</th>
            <th width="20%">業種</th>
        </tr>
    </table>
    <?php $cnt = 0;?>
    <?php foreach($resultOfranking as $ranking){?>
        <?php
        if($cnt % 20 == 0 && $cnt > 0){
            $this->partial("partial/banner_middle"); # 先に広告表示
        }
        $listedCompany = ListedCompanies::findByStockCode($ranking["stock_code"]);
        ?>
        <table id="rankinglist" width="100%">
            <tr>
                <td width="16%">
                    <a href="/index/detail/<?php echo $listedCompany->getStockCode()?>">
                        <?php echo $listedCompany->getStockCode()?>
                    </a>
                </td>
                <td width="6%">
                    <a href="/index/detail/<?php echo $listedCompany->getStockCode()?>" target="_blank">
                        <img width="20px" src="/img/new_window.png" />
                    </a>
                </td>
                <td><?php echo $listedCompany->getName()?></td>
                <td width="25%"><?php echo Stock::$marketType[$listedCompany->getMarketNo()][0]?></td>
                <td width="20%"><?php echo Stock::$industries[$listedCompany->getIndustryId()]?></td>
            </tr>
        </table>
        <?php $cnt ++;?>
    <?php }?>
</div>

