<?php

namespace app\Http\Controllers;


use Illuminate\Http\Request;

class CollectionController
{
    /**
     * Illuminate\Support\Collection 类提供一个流畅、便利的封装来操控数组数据。
     * 如下面的示例代码，我们用 collect 函数从数组中创建新的集合实例，对每一个元素运行 strtoupper 函数，然后移除所有的空元素：
     */
    public function create()
    {
        $collection = collect(['syc', 'xuz', null])->map(function ($name) {
            return strtoupper($name);
        })->reject(function ($name) {
            return empty($name);
        });
    }
    
    /**
     * 这里打印的结果
     *
     * Collection {#258 ▼
     *   #items: array:3 [▼
     *      1111 => "2332"
     *      "jaaj" => "2323"
     *      "www" => "2323"
     *       ]
     *   }
     * @param Request $request
     * 文档上有一句这样的话，"默认 Eloquent 模型的查询结果总是以 Collection 实例返回。"
     */
    public function collection1(Request $request)
    {
        $collection = collect($request->all());
        
        dd($collection);
    }
    
    /**
     * @internal param Request $request
     */
    public function collection2()
    {
        $collection = collect([
            ['name' => 'PHP: The Good Parts', 'pages' => 176],
            ['name' => 'PHP: The Definitive Guide', 'pages' => 1096],
        ]);
        dump($collection);
        //all() 返回集合的所代表的底层{数组}
        dump($collection->all());
        //avg() 返回集合中所有项目的平均WE值
        dump($collection->avg('pages'));
        //count() 返回该集合内的项目总数
        dump($collection->count());
        //将集合拆成多个指定大小的较小集合
        $collection = collect([1, 2, 3, 3, 4, 5, 43, 3, 3233, 3, 1, 23, 4, 55, 1]);
        dump($collection->chunk(2));
        dump($collection->chunk(2)->toArray());
        
    }
    
    /**
     *将多个数组组成的集合合成单个一维数组集合：
     */
    public function collapse()
    {
        $collection = collect([
            ['PHP: The Good Parts', 176],
            ['PHP: The Definitive Guide', 1096],
        ]);
        
        dump($collection);
        
        dump($collection->collapse());
        
        dump($collection);
    }
    
    /**
     * combine() 将集合的值作为「键」，合并另一个数组或者集合作为「键」对应的值。
     */
    public function combine()
    {
        $collection = collect(['name', 'age']);
        $combined = $collection->combine(['syc', 25]);
        dd($combined->all());
    }
    
    /**
     * 经过测试发现当数组为二维数组的时候传入键值对可以返回true，传入单个值是不可以的，
     * 为一维数组时传入键值对是返回false
     */
    public function contains()
    {
        $collection = collect([['name' => 'mac', 'price' => 101010], ['name' => 'win', 'price' => 1111]]);
        //false
        dump($collection->contains('mac'));
        //false
        dump($collection->contains('win'));
        //true
        dump($collection->contains('name', 'mac'));
        //
        $collection = collect([1, 45, 5, 66, 7, 8, 9, 9, 2]);
        dump($collection->contains(function ($value, $key) {
            return $value > 2;
        }));
    }
    
    /**
     * 将集合与其它集合或纯 PHP 数组 进行值的比较，返回第一个集合中存在而第二个集合中不存在的值：
     */
    public function diff()
    {
        $collection = collect([1, 2, 3, 4, 56, 34]);
        //diffKeys() 将集合与其它集合或纯 PHP 数组 的「键」进行比较，返回第一个集合中存在而第二个集合中不存在「键」所对应的键值对
        $diff = $collection->diffKeys([2, 3, 3, 5]);
        
        dump($diff->all());
        //diff() 将集合与其它集合或纯 PHP 数组 进行值的比较，返回第一个集合中存在而第二个集合中不存在的值
        $diff = $collection->diff([2, 3, 3, 5]);
        
        dump($diff->all());
        
        //each() 遍历集合中的项目，并将之传入回调函数
        //和 foreach () 有点相像
        
        $collection->each(function ($item, $key) {
            if ($item > 2) {
                dump($item);
            }
        });
        $arr = [];
        $collection->each(function ($item, $key) use (&$arr) {
            if ($item > 2) {
                $arr[$key] = $item;
                return $arr;
            }
        });
        dump($arr);
        
        //every() 创建一个包含每 第 n 个 元素的新集合 这个是官方的解释，我觉的这儿更像是n的整数倍的新集合
        dump($collection->every(1));
        //你可以选择性的传递偏移值作为第二个参数
        /* 实现的代码
         * public function every($step, $offset = 0)
            {
                $new = [];
                
                $position = 0;
                
                foreach ($this->items as $item) {
                    if ($position % $step === $offset) {
                        $new[] = $item;
                    }
                    
                    $position++;
                }
                
                return new static($new);
            }
         */
        dump($collection->every(2, 1));
        
    }
    
    /**
     *
     */
    public function except()
    {
        $collection1 = collect(['book' => 'php', 'price' => 12122, 'dis' => 1]);
        //返回集合中除了指定键以外的所有项目：
        $filtered = $collection1->except(['book']);
        
        dump($filtered);
        //使用回调函数筛选集合，只留下那些通过判断测试的项目：
        $collection = collect([1, 3, 4, 5, 6, 7, 3, 5, 5, 3, 2, 4]);
        
        $filtered = $collection->filter(function ($value, $key) {
            if ($key < 6) {
                return $value > 2;
            }
            
        });
        
        dump($filtered->all());
        //返回集合第一个通过指定测试的元素：
        $first = $collection->first(function ($value, $key) {
            return $value > 6;
        });
        dump($first);
        
        dump($collection->first());
    }
    
    public function flatMap()
    {
        $collection = collect([
            ['book'=> 'php'],
            ['price'=> 123123],
            ['ccy'=> 'CNY'],
        ]);
        //对集合内所有子集遍历执行回调，并在最后转为一维集合
        $flattened = $collection->flatMap(function ($value) {
            return array_map('strtoupper',$value);
        });
    
        dump($flattened->all());
        //将多维集合转为一维集合：
        //可以传入参数来限制降维的层数
        $flat = $collection->flatten();
        
        dump($flat->all());
    
        $collection1 = collect(['book' => 'php', 'price' => 12122, 'dis' => 1]);
        //将集合中的键和对应的数值进行互换：
        $flip = $collection1->flip();
        
        dump($flip->all());
        //通过集合的键来移除掉集合中的一个项目
        //与大多数其它集合的方法不同，forget 不会返回修改过后的新集合；它会直接修改调用它的集合。
        $collection1->forget('dis');
        
        dump($collection1->all());
    
    }
    
    public function page()
    {
        $collection = collect([1,2,3,4,5,6,7,8,9,10,11,12,13,14]);
        // === 内置的分页函数
        $chunk = $collection->forPage(2,10);
        
        dump($chunk->all());
        //根据键获取值 1.不传任何值，如果没有返回null 2.传入一个其他参数，如果没有返回默认值 3.传入回调函数，返回回调函数的运行值
        $value = $collection->get(20,'value');
        
        dump($value);
        //groupBy() 根据指定的键进行分组
        $collection = collect([
            ['id'=>1, 'gId'=> 12, 'frame'=> 'yii'],
            ['id'=>2, 'gId'=> 12, 'frame'=> 'ci'],
            ['id'=>3, 'gId'=> 13, 'frame'=> 'botstarp'],
        ]);
    }
    
    
}