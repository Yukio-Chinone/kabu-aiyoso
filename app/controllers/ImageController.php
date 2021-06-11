<?php

class ImageController extends ControllerBase
{
    /**
     * S3の画像をサーバー経由で出力 (S3パス隠蔽の為)
     * <アクセス例> http://localhost/image/show/1333/2019-02-01
     *
     * @param string $stockCode
     * @param string $ymd
     * @param string $extension
     * @param string $country
     */
    public function showAction($stockCode = "", $ymd = "", $extension = "png", $country = "japan")
    {
        if (empty($stockCode) || empty($ymd)) {
            echo "error.";
            exit();
        }

        $filePath = sprintf(Stock::S3_PATH_FORMAT, $country, $stockCode, $stockCode, $ymd, $extension);
        //echo $filePath;exit();
        header('Content-Disposition: inline; filename="' . $stockCode . '"');
        header('Content-type: image/png');
        readfile($filePath);

        $this->view->disable();
    }
}

