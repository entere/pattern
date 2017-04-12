<?php
namespace Entere\Cashier;

use Entere\Cashier\CashNormal;
use Entere\Cashier\CashRebate;
use Entere\Cashier\CashReturn;

//工厂与策略结合
class CashContext {

    private $_strategy = null; //策略

    public function __construct($type = null) {
        if (!isset($type)) {  
            return;  
        }  
        $this->setCashStrategy($type);  
        
    }

    /** 
     * 设置策略（简单工厂与策略模式混合使用） 
     * 
     * @param string $type 类型 
     * @return void 
     */  

    public function setCashStrategy($type) {
        $cs = null;
        switch ($type) {
            // 正常策略  
            case 'normal':
                $cs = new CashNormal();
                break;

            // 打折策略  
            case 'rebate8':
                $cs = new CashRebate(0.8);
                break;

            // 返利策略  
            case 'return300to100':
                $cs = new CashReturn(300, 100);
                break;
            
            default:
                $cs = new CashNormal();
                break;
        }

        $this->_strategy = $cs;
    }

    /** 
     * 获取结果 
     * 
     * @param double $money 金额 
     * @return double 
     */  
    public function getResult($money){  
        return $this->_strategy->acceptCash($money);  
    } 

     /** 
     * 获取结果 
     * 
     * @param string $type 类型 
     * @param int $num 数量 
     * @param double $price 单价 
     * @return double 
     */  
    public function getResultAll($type, $num, $price){  
        $this->setCashStrategy($type);  
        return $this->getResult($num * $price);  
    }   


}