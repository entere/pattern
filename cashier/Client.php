<?php
namespace Entere\Cashier;

require_once("./src/ICashSuper.php");
require_once("./src/CashNormal.php");
require_once("./src/CashRebate.php");
require_once("./src/CashReturn.php");
require_once("./src/CashContext.php");

use Entere\Cashier\CashContext;

class Client {
    public function main() {

        $total = 0;   
        $cashContext = new CashContext();  
          
        // 购买数量  
        $numA = 10;  
        // 单价  
        $priceA = 100;  
        // 策略模式获取结果  
        $totalA = $cashContext->getResultAll('normal', $numA, $priceA);  
        $this->display('A', 'normal', $numA, $priceA, $totalA);  
        


        // 购买数量  
        $numB = 5;  
        // 单价  
        $priceB = 100;  
        // 打折策略获取结果  
        $totalB = $cashContext->getResultAll('rebate8', $numB, $priceB);  
        $this->display('B', 'rebate8', $numB, $priceB, $totalB);  
          


        // 购买数量  
        $numC = 1;  
        // 单价  
        $priceC = 400;  
        // 返利策略获取结果  
        $totalC = $cashContext->getResultAll('return300to100', $numC, $priceC);  
        $this->display('C', 'return300to100', $numC, $priceC, $totalC);  
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
    $client = new Client();  
    $client->main();  
}  
  
start();  
?>