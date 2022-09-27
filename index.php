<?php
require_once('/wordAnalysis/textSimilarity.class.php');

// 获取两个词的相似度
function getTextSimilarity($text1, $text2)
{
    $obj = new TextSimilarity($text1, $text2);
    $similarity = $obj->run();
    return  $similarity;
}
