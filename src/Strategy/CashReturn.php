<?php
namespace Entere\Pattern\Strategy;

use Entere\Pattern\Strategy\ICashSuper;

// 返利策略  
class CashReturn implements ICashSuper {

    
    private $_moneyCondition = 0;//返利条件

    private $_moneyReturn = 0;//返利钱数

    /**
     * 比如满300减100
     * @param  double $moneyCondition 300
     * @param  double $moneyReturn     100
     * @return                 
     */
    public function __construct($moneyCondition, $moneyReturn) {
        $this->_moneyCondition = $moneyCondition;
        $this->_moneyReturn = $moneyReturn;
    }

    /**
     * 返回满300减100后的金额 
     * @param  double $money 原价
     * @return double        满300减100后的金额
     */
    public function acceptCash($money) {
        $result = $money;  
        
        if ($money >= $this->_moneyCondition)  
        {  
            $result = $money - intval($money / $this->_moneyCondition) * $this->_moneyReturn;  
        }  
        return $result;  
    }

}