<?php
namespace Entere\Pattern\Strategy;

interface ICashSuper {

    /**
     * 计算价钱
     * @param  double $money 
     * @return [type]        [description]
     */
    public function acceptCash($money);


}