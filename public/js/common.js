/**
 * 非同期送信(JQuery版)
 * ・HTML側で以下が必要
 * ・<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 */
function getRequest(url, user_id, stock_code, status, ymd) {

    if (user_id <= 0) {
        alert("この操作をするには ”ログイン” が必要です。");
        return;
    }

    ON_CLASS = "btn btn-primary"
    OFF_CLASS = "btn btn-outline-primary"
    jQuery.ajax(
        url, {
            type: "POST",
            dataType: 'text',
            data: {//nameとvalueをセットでオブジェクトのなかに定義する
                "stock_code": stock_code,
                "user_id": user_id,
                "status": status,
                "ymd": ymd,
            },
            success: function (post) {
                result = JSON.parse(post);
                //console.log(result.status);
                class_1 = OFF_CLASS
                class_2 = OFF_CLASS
                class_3 = OFF_CLASS
                class_4 = OFF_CLASS

                if (result.status == 1) {
                    class_1 = ON_CLASS
                } else if (result.status == 2) {
                    class_2 = ON_CLASS
                } else if (result.status == 3) {
                    class_3 = ON_CLASS
                } else if (result.status == 4) {
                    class_4 = ON_CLASS
                }

                document.getElementById("favorite_" + stock_code + "_1").className = class_1;
                document.getElementById("favorite_" + stock_code + "_2").className = class_2;
                document.getElementById("favorite_" + stock_code + "_3").className = class_3;
                document.getElementById("favorite_" + stock_code + "_4").className = class_4;
            },
        }
    );
}

/**
 * 非同期送信(JQuery版)
 * ・HTML側で以下が必要
 * ・<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 */
function getRequestChecked(url, user_id, stock_code, ymd) {

    if (user_id <= 0) {
        alert("この操作をするには ”ログイン” が必要です。");
        return;
    }

    jQuery.ajax(
        url, {
            type: "POST",
            dataType: 'text',
            data: {//nameとvalueをセットでオブジェクトのなかに定義する
                "stock_code": stock_code,
                "user_id": user_id,
                "ymd": ymd,
            },
            success: function (post) {
                result = JSON.parse(post);
                if (result.checked_at == "") {
                    alert("予想日を保存するには、この銘柄を”お気に入り” に登録する必要があります。");
                    document.getElementById('id-' + ymd).checked = false;
                }
                //console.log(result.checked_at);
            },
        }
    );
}

/**
 * 非同期送信(JQuery版)
 * ・HTML側で以下が必要
 * ・<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
 */
function getSearchCondition(url, stock_code, ymd) {

    c = document.getElementById("search_" + ymd + "_" + stock_code);

    if (c.style.display == "block") {
        c.style.display = "none";
    } else {
        c.style.display = "block";

        jQuery.ajax(
            url, {
                type: "POST",
                dataType: 'text',
                data: {//nameとvalueをセットでオブジェクトのなかに定義する
                    "stock_code": stock_code,
                    "ymd": ymd,
                },
                success: function (post) {
                    result = JSON.parse(post);
                    if (result === undefined || result.length == 0) {
                        //console.log("データ無し")
                    } else {
                        keys = Object.keys(result);
                        for (k = 0; k < keys.length; k++) {

                            key = keys[k]
                            subkeys = Object.keys(result[keys[k]]);

                            for (sk = 0; sk < subkeys.length; sk++) {

                                try {
                                    subkey = subkeys[sk]
                                    targetId = "search_" + ymd + "_" + stock_code + "_" + key + "_" + subkey;
                                    to = document.getElementById(targetId);
                                    //console.log(key);
                                    //console.log(subkey);
                                    //console.log(result[key][subkey]);
                                    //console.log(targetId)

                                    if (result[key][subkey] == 1) {
                                        to.style.display = "block";
                                        //console.log("block ："　+ targetId);
                                    } else {
                                        to.style.display = "none";
                                        //console.log("none ："　+ targetId);
                                    }
                                } catch (error) {
                                    console.log("not found ：" + targetId);
                                }
                            }
                        }
                    }
                },
            }
        );
    }
}

/**
 * 指定URLへジャンプ
 * @param url
 */
function jumpUrl(url) {
    location.href = url;
}

/**
 * 指定IDの表示/非表示
 * @param id
 */
function dspOnOff(id) {

    var c = document.getElementById(id);

    if (c.style.display == "block") {
        c.style.display = "none";
    } else {
        c.style.display = "block";
    }
}

/**
 * 画像の高さ変更
 * @param stockCode
 */
function biggerImg(stockCode, ymd) {

    imgObj = document.getElementById("img_stock" + stockCode + "_" + ymd);
    btnObj = document.getElementById("imgbtn_stock" + stockCode + "_" + ymd);
    height = imgObj.height;
    //console.log("img height ：" + height);

    if (btnObj.innerHTML == "+") {
        btnObj.innerHTML = "-";
        imgObj.style.height = height * 2
    } else {
        btnObj.innerHTML = "+";
        imgObj.style.height = height / 2
    }

}

/**
 * オプションエリアの開閉
 * @param id
 */
function optionArea(id) {

    if (document.getElementById(id).style.display == "none") {
        document.getElementById(id).style.display = "block";
        document.searchForm.option_area.value = "open";
    } else {
        document.getElementById(id).style.display = "none";
        document.searchForm.option_area.value = "";
    }

}

/**
 * エリアの開閉
 * @param id
 */
function openClose(openid, closeid) {
    document.getElementById(openid).style.display = "block";
    document.getElementById(closeid).style.display = "none";
}

/**
 * チェックボックスのOFF
 */
function offCheckbox(id){
    document.getElementById(id).checked = false;
}