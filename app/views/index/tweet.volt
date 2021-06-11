<h4 id="searchTop" class="d-flex justify-content-start align-items-center mt-4 mb-1">
    <?php if(!empty($prevLink)){?>
    <a href="<?php echo $prevLink?>">銘柄検索</a>&nbsp;&gt;&nbsp;
    <?php }else{?>
    <a href="/#">銘柄検索</a>&nbsp;&gt;&nbsp;
    <?php }?>
    <span><?php echo $subTitle?></span>
</h4>
<div class="rounded border bg-white p-3 mb-3 ">

    <div class="tab">
        <a href="/index/detail/<?php echo $stockCode?>#searchTop">チャート</a>
        <a class="active" href="/index/tweet/<?php echo $stockCode?>#searchTop">ツイート</a>
    </div>
    <div class="floatclear tab_bottom"><hr /></div>

    <?php foreach($tweets as $tweet){?>
    <?php echo $tweet;?>
    <hr />
    <?php }?>







</div>

<?php $this->partial("partial/menu_bottom")?>
