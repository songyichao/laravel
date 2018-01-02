<?php

namespace App\Http\Controllers;

//class A
//{
//    public static function call()
//    {
//        echo 'class A <br>';
//    }
//
//    public static function test()
//    {
//        self::call();
//        static::call();
//    }
//}
//
//class B extends A
//{
//    public static function call()
//    {
//        echo 'class B <br>';
//    }
//}
//
//B::test();

/*
 * 非静态类
 */
//class C
//{
//    public function call()
//    {
//        echo 'instance c <br>';
//    }
//
//    public function test()
//    {
//        self::call();
//        static::call();
//    }
//}
//
//class D extends C
//{
//    public function call()
//    {
//        echo 'instance d <br>';
//    }
//}
//
//(new D())->test();

class E
{
    public static function create()
    {
        $self = new self();
        $static = new static ();

        return [$self, $static];
    }
}

class F extends E
{

}

$arr = F::create();
foreach ($arr as $item) {
    dump($item);
}