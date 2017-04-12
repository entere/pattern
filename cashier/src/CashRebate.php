<?php

namespace Entere\Cashier;

use Entere\Cashier\ICashSuper;

// 折扣收取策略  
class CashRebate implements ICashSuper {


    private $_moneyRebate = 1; //折扣点数 0<$_moneyRebate<=1

    public function __construct($moneyRebate) {
        $this->_moneyRebate = $moneyRebate;
    }


    /**
     * 计算折扣后的报价 
     * @param  double $money 商品原价
     * @return double        返回折扣后的报价
     */
    public function acceptCash($money) {
        return $this->_moneyRebate * $money;
    }

}