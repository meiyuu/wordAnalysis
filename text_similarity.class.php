<?php
/*
*   求两个文本的相似度（余弦定理）
*   参考：
*   http://www.ruanyifeng.com/blog/2013/03/cosine_similarity.html
*
*   Use:
*   $obj = new TextSimilarity ($text1, $text2);
*   echo $obj->run();
*/
require_once('/wordAnalysis/textSimilarity.class.php');

class TextSimilarity
{
    /**
     * [排除的词语]
     *
     * @var array
     */
    private $_excludeArr = array('的', '了', '和', '呢', '啊', '哦', '恩', '嗯', '吧');

    /**
     * [词语分布数组]
     *
     * @var array
     */
    private $_words = array();

    /**
     * [分词后的数组一]
     *
     * @var array
     */
    private $_segList1 = array();

    /**
     * [分词后的数组二]
     * @var array
     */
    private $_segList2 = array();

    /**
     * [分词两段文字]
     */
    public function __construct($text1, $text2)
    {
        $this->_segList1 = $this->segment($text1);
        $this->_segList2 = $this->segment($text2);
    }

    /**
     * [外部调用]
     */
    public function run()
    {
        $this->analyse();
        $rate = $this->handle();
        return $rate ? $rate : 0;
    }

    /**
     * [分析两段文字]
     */
    private function analyse()
    {
        //t1
        foreach ($this->_segList1 as $v) {
            if (!in_array($v, $this->_excludeArr)) {
                if (!array_key_exists($v, $this->_words)) {
                    $this->_words[$v] = array(1, 0);
                } else {
                    $this->_words[$v][0] += 1;
                }
            }
        }

        //t2
        foreach ($this->_segList2 as $v) {
            if (!in_array($v, $this->_excludeArr)) {
                if (!array_key_exists($v, $this->_words)) {
                    $this->_words[$v] = array(0, 1);
                } else {
                    $this->_words[$v][1] += 1;
                }
            }
        }
    }

    /**
     * [处理相似度]
     */
    private function handle()
    {
        $sum = $sumT1 = $sumT2 = 0;
        foreach ($this->_words as $word) {
            $sum    += $word[0] * $word[1];
            $sumT1  += pow($word[0], 2);
            $sumT2  += pow($word[1], 2);
        }

        $rate = $sum / (sqrt($sumT1 * $sumT2));
        return $rate;
    }

    /**
     * 分词
     */
    private function segment($text)
    {
        $outText = [];
        $pa = new PhpAnalysis('utf-8', 'utf-8', false);
        $pa->LoadDict();
        $pa->SetSource($text);
        $pa->StartAnalysis(false);
        $result = $pa->GetFinallyResult();
        $result = explode(' ', $result);
        // 遍历出需要的数组
        foreach ($result as $value) {
            if (!empty($value)) {
                $outText[] = $value;
            }
        }
        return $outText;
    }
}
