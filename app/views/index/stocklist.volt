<h4 class="d-flex justify-content-start align-items-center mb-1">
    <span>取り扱い銘柄一覧</span>
</h4>

<div class="rounded border bg-white p-3 mb-3">

    <div class="row">
        <div class="col-md-12 mt-3 mb-1">

            <?php $cnt = 0;?>

            <?php foreach($searchResults as $searchResult){ // ########## 銘柄数分ループ ########## ?>
            <?php
            $stockCode = $searchResult->getStockCode();
            $stockName = $searchResult->getName();

            if($cnt % 20 == 0 && $cnt > 0){
                $this->partial("partial/banner_middle"); # 先に広告表示
            }
            ?>

            <table id="rankinglist" width="100%">
                <tr>
                    <td width="16%">
                        <a href="/index/detail/<?php echo $stockCode?>">
                            <?php echo $stockCode?>
                        </a>
                    </td>
                    <td width="6%">
                        <a href="/index/detail/<?php echo $stockCode?>" target="_blank">
                            <img width="20px" src="/img/new_window.png" />
                        </a>
                    </td>
                    <td width="78%">
                        <?php echo $stockName?>
                    </td>
                </tr>
            </table>
            <?php $cnt ++;?>

            <?php }?>
        </div>

    </div>

</div>

<div class="mb-3">
    <?php $this->partial("partial/banner_bottom")?>
</div>


