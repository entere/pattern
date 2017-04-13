<?php

require_once("../src/Strategy/ICashSuper.php");
require_once("../src/Strategy/CashNormal.php");
require_once("../src/Strategy/CashRebate.php");
require_once("../src/Strategy/CashReturn.php");
require_once("../src/Strategy/CashContext.php");

use Entere\Pattern\Strategy\CashContext;

class ClientStrategy {
    public function main() {

        $total = 0;   
        $cashContext = new CashContext();  
          
        // 购买数量  
        $numA = 10;  
        // 单价  
        $priceA = 100;  
        // 策略模式获取结果  
        $totalA = $cashContext->getResultAll('正常收费', $numA, $priceA);  
        $this->display('A', '正常收费', $numA, $priceA, $totalA);  
        


        // 购买数量  
        $numB = 5;  
        // 单价  
        $priceB = 100;  
        // 打折策略获取结果  
        $totalB = $cashContext->getResultAll('打8折', $numB, $priceB);  
        $this->display('B', '打8折', $numB, $priceB, $totalB);  
          


        // 购买数量  
        $numC = 1;  
        // 单价  
        $priceC = 400;  
        // 返利策略获取结果  
        $totalC = $cashContext->getResultAll('满300返100', $numC, $priceC);  
        $this->display('C', '满300返100', $numC, $priceC, $totalC);  
    }

    /** 
     * 打印 
     * 
     * @param string $name 商品名 
     * @param string $type 类型 
     * @param int $num 数量 
     * @param double $price 单价 
     * @return double 
     */  
    public function display($name, $type, $num, $price, $total){  
        echo date('Y-m-d H:i:s') . ",$name,[$type],num:$num,price:$price,total:$total \r\n";
        echo "<br>";  
    }  
}


/** 
 * 程序入口 
 */  
function start(){  
    $clientStrategy = new ClientStrategy();  
    $clientStrategy->main();  
}  
  
start();  
?>