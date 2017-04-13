
# 《大话设计模式》php版：策略模式

时间：2月27日22点  地点：大鸟房间  人物：小菜、大鸟

# 2.1 商场收银软件

“小菜，给你出个作业，做一个商场收银软件，营业员根据客户所购买商品的单价和数量，向客户收费。”


“就这个？木问题。”小菜说，“用两个文本框来输入单价和数量，一个确定按键来算出每种商品的费用，用个列表框来记录商品的清单，一个标签来记录总计，对，还需要一个重置控制来重新开始，不就行了？！”
 

商场收银系统 v1.0 关键代码如下：

```php
<?php
//Cash.php
class Cash  {  
    private $total = 0;  
    public function submit($num, $price)  
    {  
        $totalPrices = $num * $price;  
        $this->total += $totalPrices;  
        printf("单价：%s，数量：%s，合计：%s", $price, $num, $totalPrices);   
    }  
} 
$obj = new Cash();
$obj->submit(2,39);

?>

```

“大鸟，”小菜叫道，“来看看，这不就是你要的收银软件吗？我不到半个小时就搞定了啦。”


“哈哈，挺快的嘛。”大鸟说着，看了看小菜的代码。接着说：“现在我要求商场对商品搞活动，所有的商品打 8 折。”


“那不就是在 totalPrices 后面乘以个 0.8 吗？”


“小子，难道商场活动结束，不打折了，你还要再改一遍代码，然后再用改后的程序把所有的机器全部安装一次吗？再说，还有可能因为周年庆，打五折的情况，怎么办？”
小菜不好意思道：“啊，我想的是简单了点。其实呢，只要增加一个下拉菜单选项框就可以解决你说的问题啦。”


大鸟笑而不语。

# 2.2 增加打折

商场收银系统 v1.1 关键代码如下：
 
 
```php
<?php
//Cash.php
class Cash  {  
    private $total = 0; 
    
    public function submit($rebateCondition, $num, $price)  
    { 
        $totalPrices = 0;  
        switch ($rebateCondition)  
        {  
            case "正常收费":  
                $totalPrices = $num * $price;  
                break;  
            case "打8折":  
                $totalPrices = $num * $price * 0.8;  
                break;  
            case "打7折":  
                $totalPrices = $num * $price * 0.7;  
                break;  
            case "打5折":  
                $totalPrices = $num * $price * 0.5;  
                break; 
            default:
                $totalPrices = $num * $price;
                break; 
        }   
          
        $this->total += $totalPrices;  
        printf("单价：%s，数量：%s，折扣方式：%s，合计：%s ", $price, $num, $rebateCondition, $totalPrices);   
    }  
} 
$obj = new Cash();
$obj->submit("打8折",2,39);

?>
```


“这下可以了吧，只要我事先把商场可能的打折都做成下拉菜单的样子，就可以了”小菜说道。

“这比刚才灵活性上是好了，不过重复代码很多，4 个分支语句除了打折多少不同以外几乎完全一样，应该考虑重构一下。不过这还不是最主要的，现在我的需求又来了，商场的活动加大，需要有满300返100的促销算法，你说该怎么办？”

“满 300 返 100，那要是 700 就要返 200 了？这个必须要写成函数了吧？”

“小菜啊，看来之前教你的都白教了，这里面看不出来什么名堂吗？”

“哦！我想起来了，你的意思是简单工厂模式，对对对，我可以先写一下父类，再继承它实现多个打折和反利的子类，复用多态性来完成这个代码。”

“那你打算写几个子类？”

“根据需求嘛，比如 8 折、7 折、5 折、满 300 送 100、满 200 送 50…要几个写几个。”

“小菜又不动脑子了，有必要这样写吗？如果我现在是 3 折，我要满 300 送 80，你难道再去增加子类？你不想想看，这当中哪些是相同的，哪些是不同的？”

# 2.3 简单工厂实现

大鸟：“对的，这里打折基本都是一样的，只要有个初始化参数就可以了。满几送几的，需要两个参数才行，明白，现在看来不麻烦了。”

大鸟：“面向对象的编程，并不是类越多越好，类的划分是为了封装，但分类的基础是抽象，具有相同的属性和功能的对象的抽象集合才是类。打1折和打9折只是形式不同，抽象分析出来，所有的打折算法都是一样的，所以打折算法应该是一个类。好了，空话说的太多了，写出来才是硬道理。”

大约1个小时后，小菜交出了第三份作业。

代码结构图

![代码结构图](http://hi.csdn.net/attachment/201006/19/0_1276989870q5S1.gif)
 
ICashSuper.php

```php

<?php
namespace Entere\Pattern\Strategy;

//现金收钱接口 
interface ICashSuper {
    /**
     * 计算价钱
     * @param  double $money 
     * @return [type]        [description]
     */
    public function acceptCash($money);


} 
?>

```


CashNormal.php

```php

<?php
namespace Entere\Pattern\Strategy;

use Entere\Pattern\Strategy\ICashSuper;

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
?>

```

CashRebate.php

```php
<?php
namespace Entere\Pattern\Strategy;

use Entere\Pattern\Strategy\ICashSuper;

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
?>

```
CashReturn.php

```php

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
        if ($money >= $this->_moneyCondition)  {  
            $result = $money - intval($money / $this->_moneyCondition) * $this->_moneyReturn;  
        }  
        return $result;  
    }

}
?>

```

CashFactory.php

```php

<?php
//现金收费工厂类
namespace Entere\Pattern\Strategy;

use Entere\Pattern\Strategy\CashNormal;
use Entere\Pattern\Strategy\CashRebate;
use Entere\Pattern\Strategy\CashReturn;
  
class CashFactory  {  
    public static function createCash($type)  
    {  
        $cs = null;  
        if ("正常收费" == $type)  {  
            $cs = new CashNormal();  
        }  else if ("满300返100" == $type)  {  
            $cs = new CashReturn(300, 100);  
        }  else if ("打8折" == $type)  {  
            $cs = new CashRebate(0.8);  
        }  
          
        return $cs;  
    }  
}
?>

```
Client.php

```php

<?php  
//客户端代码  
class Client  
{  
    private $total   = 0;  
  
    public function main()  
    {  
        $this->consume("正常收费", 1, 1000);  
        $this->consume("满300返100", 1, 1000);  
        $this->consume("打8折", 1, 1000);  
        printf("总计：%s", $total);  
    }  
  
    public function consume($type, $num, $price)  
    {  
        $csuper = CashFactory::createCash(type);  
        $totalPrices = 0;  
        $totalPrices = $csuper->acceptCash(num * price);  
        $total += $totalPrices;  
        printf("单价：%s，数量：%s，折扣方式：%s，合计：%s ", $price, $num, $type, $totalPrices); 
    }  
} 
?>

```

“搞定，这次无论你要怎么改，我都可以简单处理完成。”小菜自信满满地说。

“是吗？我要的是需要打5折和满500送200的促销活动，如何办？”

“只要在现金工厂中加两个条件，在在界面的下拉菜单中增加两项即可。”

“说的不错，如果我现在需要增加一种商场促销手段，满 100 积分 10 点，以后积分到一定时候可以领取奖品如何做？”

“有了工厂，何难？加一个积分算法，构造方法有两个参数：条件和返点，让它继承 ICashSuper，再到现金工厂增加满 100 积分 10 点的分支条件，再到界面稍加改动就行了。”

“嗯，不错，你的简单工厂模式运用的很熟练了嘛，简单工厂模式虽然也能解决这个问题，但是这个模式只是解决对象的创建问题，而且由于工厂本身包括了所有的收费方式，商场是可能经常性地更改打折额度和返利额度的，每次维护或扩展收费方式都要改动这个工厂，以致代码需要重新编译部署，这是非常糟糕的处理方式，所以用它不是最好的办法。面对算法的时常变动，应该有更好的办法。好好去研究一下其他的设计模式，你会找到答案的。”

小菜进入沉思状态…

# 2.4 策略模式

小菜次日找到大鸟：“我找到相关的设计模式了，应该是策略模式（Strategy）。策略模式定义了算法家族，分别封装起来，让它们之间可以互相替换，此模式让算法的，不会影响到使用算法的客户。看来商场收银系统应该考虑用策略模式？”

策略模式（Strategy）：它定义了算法家族，分别封装起来，让它们之间可以互相替换，此模式让算法的变化，不会影响到使用算法的客户。

“你问我？你说呢？”大鸟笑道：“商场收银时，如何促销，用打折还是返利，其实都是一些算法，用工厂来生成算法对象，这没有错，但算法本身只是一种策略，最重要的是这些算法是随时都可能互相替换的，就这点变化，而封装变化点是我们面向对象的一种很重要的思维方式。我们来看看策略模式的结构图和基本代码。”

策略模式（Strategy）结构图

![策略模式（Strategy）结构图](http://hi.csdn.net/attachment/201006/19/0_12769899720BOF.gif)

```php

<?php
//Strategy类，定义所有支持的算法的公共接口  
interface Strategy  
{  
    public function algorithmInterface();  
}  
//ConcreteStrategy封装了具体的算法或行为，继承于Strategy  
class ConcreteStrategyA implements Strategy  
{  
    public function algorithmInterface()  
    {  
        echo ("算法A实现");  
    }  
}  
class ConcreteStrategyB implements Strategy  
{  
    public function algorithmInterface()  
    {  
        echo ("算法B实现");   
    }  
}  
class ConcreteStrategyC implements Strategy  
{  
    public function algorithmInterface()  
    {  
        echo ("算法C实现");  
    }  
}  
//Context用一个ConcreteStrategy来配置，维护一个对Strategy对象的引用  
class Context  
{  
    private $strategy;  
  
    public function __construct($strategy)  
    {  
        $this->strategy = $strategy;  
    }  
  
    public function contextInterface()  
    {  
        $this->strategy->algorithmInterface();  
    }  
}  
//客户端代码  
class Main  
{  
    public static function main()  
    {  
        private $context = null;  
        $context = new Context(new ConcreteStrategyA());  
        $context->contextInterface();  
          
        $context = new Context(new ConcreteStrategyB());  
        $context->contextInterface();  
          
        $context = new Context(new ConcreteStrategyC());  
        $context->contextInterface();  
    }  
} 
?>

```

# 2.5 策略模式实现

小菜：“我明白了，我昨天写的 CashSuper 就是抽象策略，而正常收费 CashNormal、打折收费 CashRebate 和返利收费 CashReturn 就是三个具体策略，也就是策略模式中说的具体算法，对吧？”

“是的哇，来吧，你模仿策略模式的基本代码，改动一下你的程序。”

“其实不麻烦的说，原来写的 ICashSuper、CashNormal、CashRebate和 CashReturn 都不用改了，只要加一个 CashContext 类，并改一下客户端就可以了。”

商场收银系统v1.2

代码结构图

![代码结构图](http://hi.csdn.net/attachment/201006/19/0_1276990048DO8H.gif)

```php

<?php
//CashContext类  
class CashContext  
{  
    private $cashSuper;  
  
    public function __construct($cashSuper)  
    {  
        $this->cashSuper = $cashSuper;  
    }  
  
    public function acceptCash($money)  
    {  
        return $this->cashSuper->acceptCash($money);  
    }  
}  
//客户端代码  
class Client  
{  
    private $total   = 0;  
  
    public function main()  
    {  
        $this->consume("正常收费", 1, 1000);  
        $this->consume("满300返100", 1, 1000);  
        $this->consume("打8折", 1, 1000);  
  
        printf("总计：%s", $total);  
    }  
  
    public function consume($type, $num, $price)  
    {  
        $cashContext = null;  
  
        if ("正常收费" == $type)  
        {  
            $cashContext = new CashContext(new CashNormal());  
        }  
        else if ("满300返100" == $type)  
        {  
            $cashContext = new CashContext(new CashReturn(300, 100));  
        }  
        else if ("打8折" == $type)  
        {  
            $cashContext = new CashContext(new CashRebate(0.8));  
        }  
  
        $totalPrices = $cashContext->acceptCash($num * $price);  
        $total += $totalPrices;  
          
        printf("单价：%s，数量：%s，折扣方式：%s，合计：%s ", $price, $num, $type, $totalPrices);   
    }  
} 

?>
```


“大鸟，代码虽然是模仿出来了，但我感觉还是回到原来的老路子了，在客户端判断用哪一个算法。”

“是的，但是你有没有什么好办法，把这个判断过程从客户端程序中转移走呢？”

“转移？不明白，原来我用简单工厂模式可以转移，现在这样子如何做？”

“难道简单工厂就一定要是一个单独的类吗？难道不可以与策略模式的 Context 结合?”

“我明白了，试试。”

# 2.6 策略与简单工厂结合

 
```php

<?php
namespace Entere\Pattern\Strategy;

use Entere\Pattern\Strategy\CashNormal;
use Entere\Pattern\Strategy\CashRebate;
use Entere\Pattern\Strategy\CashReturn;

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
            case '正常收费':
                $cs = new CashNormal();
                break;

            // 打折策略  
            case '打8折':
                $cs = new CashRebate(0.8);
                break;

            // 返利策略  
            case '满300返100':
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
?>

```

```php

<?php



<?php

class Client {
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
    $client = new Client();  
    $client->main();  
}  
  
start();  
?>

``` 

“嗯，原来简单工厂模式并非只有建一个工厂类的做法，还可以这样子做。此时比刚才的模仿策略模式的写法要清楚多了，客户端代码简单明了。”

“那和你写的简单工厂客户端代码相比，观察一下，找出它们的不同之外。”
 
```php
<?php

//简单工厂模式的用法  
$csuper = CashFactory::createCash(type);    
$totalPrices = $csuper->acceptCash($num * $price);  
  
//策略模式与简单工厂模式结合的用法  
$cashContext = new CashContext($type); 
$totalPrices = $cashContext->acceptCash($num * $price); 
?>
``` 

“你的意思是，简单工厂模式我需要让客户端认识两个类，CashSuper 和 CashFactory，而策略模式与简单工厂模式结合的用法，客户端就只需要认识一个类 CashContext。耦合度更加降低。”

“说的木有错，我们在客户端实例化的是 CashContext 的对象，调用的是 CashContext 的方法，这使得具体的收费算法彻底地与客户端分离。连算法的父类 CashSuper 都不让客户端认识了。相当于创建了一个句柄类。”

# 2.7 策略模式解析

大鸟：“回过头来反思一下策略模式，策略模式是一种定义一系列算法的方法，从概念上来看，所有这些算法完成的都是相同的工作，只是实现不同，它可以以相同的方式调用所有的算法，减少了各种算法与使用算法之间的耦合。”

小菜：“策略模式还有什么优点？”

大鸟：“策略模式的 Strategy 类层次为 Context 定义了一系列的可供重用的算法或行为。继承有助于析取出这些算法中的公共功能。对于打折、返利或者其他的算法，其实都是对实际商品收费的一种计算方式，通过继承，可以得到它们的公共功能，你说这公共功能指虾米？”

小菜：“公共的功能就是获得计算费用的结果 getResult，这使得算法间有了抽象的父类 CashSuper。”

大鸟：“不错，另外一个策略模式的优点是简化了单元测试，因为每个算法都有自己的类，可以通过自己的接口单独测试。”

小菜：“每个算法可保证它没有错误，修改其中任一个时也不会影响其他的算法，这真是的非常的好。”

大鸟：“哈，小菜今天表现的不错，我所想的你都想到了。还有，在最开始编程时，不得不在客户端的代码中为了判断用哪一个算法计算而用了if条件分支，这也是正常的。因为当不同的行为堆砌于一个类中，就很难避免使用条件语句来选择合适的行为。将这些行为封装在一个个独立的 Strategy 类中，可以在使用这些行为的类中消除条件语句。就商场收银系统的例子而言，在客户端的代码中就消除条件语句，避免了大量的判断。这是非常重要的进展。你能用一句话来概况这个优点吗？”

小菜：“策略模式封装了变化。”

大鸟：“说的非常好，策略模式就是用来封装算法的，但在初中中，我们发现可以用它来封装几乎任何类型的规则，只要在分析过程中听到需要在不同时间应用不同的业务规则，就可以考虑使用策略模式处理这种变化的可能性。”

小菜：“但我感觉在基本的策略模式中，选择所用具体实现的职责由客户端对象承担，并转给策略模式的 Context 对象。这本身并没有解除客户端需要选择判断的压力，而策略模式与简单工厂模式结合后，选择具体实现的职责也可以由Context来承担，这就最大化地减轻了客户端的职责。”

大鸟：“是的，这已经比起初的策略模式好胜了，不过，它依然不够完美。”

小菜：“还有什么不足吗？”

大鸟：“因为在 CashContext 里还是用到了 if 或 switch，也就是说，如果我们需要增加一种算法，比如满 200 返 50，你就必须要改 CashContext 中的 if 或 switch 代码，这让人感觉很不 happy 的说！”

小菜：“啊，那你说怎么办，有需求就得改啊，任何需求的变更都是需要成本的。”

大鸟：“是的哇，但是成本的高低还是有差异的嘛。高手和菜鸟的区别就是高手可以花同样的代价获得最大的收益或者说做同样的事花最小的代价。面对同样的需求，当然是改动越小越好啦。”

小菜：“你的意思是说，还有更好的办法？”

大鸟：“当然啦，要不然我怎么会这样说哩，这个办法就是用到了反射技术，不是常有人讲：反射反射，程序员的快乐，不过今天就不讲了，以后会再提它的。”

“反射真的有这么神奇？”小菜疑惑地望向了远方。

（注：在抽象工厂模式章节有对反射的讲解）


# 源码

[本文源码请点此查看](https://github.com/entere/pattern/tree/master/src/Strategy)

