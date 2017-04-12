<?php

namespace Entere\Cashier;

use Entere\Cashier\ICashSuper;

// 正常收钱策略  
class CashNormal implements ICashSuper {

    /**
     * 返回商品正常价，好东西不打折 
     * @param  [double] $money 商品正常价
     * @return [double]        返回正常价
     */
    public function acceptCash($money) {
        return $money;
    }

}