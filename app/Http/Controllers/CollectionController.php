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
            ['book' => 'php'],
            ['price' => 123123],
            ['ccy' => 'CNY'],
        ]);
        //对集合内所有子集遍历执行回调，并在最后转为一维集合
        $flattened = $collection->flatMap(function ($value) {
            return array_map('strtoupper', $value);
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
        $collection = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]);
        // === 内置的分页函数
        $chunk = $collection->forPage(2, 10);

        dump($chunk->all());
        //根据键获取值 1.不传任何值，如果没有返回null 2.传入一个其他参数，如果没有返回默认值 3.传入回调函数，返回回调函数的运行值
        $value = $collection->get(20, 'value');

        dump($value);
        //groupBy() 根据指定的键进行分组
        $collection = collect([
            ['id' => 1, 'gId' => 12, 'frame' => 'yii'],
            ['id' => 2, 'gId' => 12, 'frame' => 'ci'],
            ['id' => 3, 'gId' => 13, 'frame' => 'botstarp'],
        ]);

        $grouped = $collection->groupBy('gId');

        dump($grouped->toArray());

        $collection = collect(['email' => 'songyichao74@gmail.com', 'tel' => 111111111, 'sex' => 'man']);

        dump($collection->has('name'));

        $collection = collect([
            ['uid' => 'ashdajsdad', 'username' => 'sssss'],
            ['uid' => '2222asdasd', 'username' => 'wwwww'],
        ]);
        //implode 类似于php的implode
        dump($collection->implode('username', ','));

        $collection = collect([1, 3, 2, 4, 567, 6, 8]);

        dump($collection->implode('_'));

        $collection = collect(['syc', 'wy', 'lgc']);
        //intersect 计算交集
        $intersect = $collection->intersect(['syc', 'lgc']);

        dump($intersect->all());

        dump($intersect->isEmpty());

    }

    public function key()
    {
        $collection = collect([
            ['uid' => 'ashdajsdad', 'username' => 'sssss'],
            ['uid' => '2222asdasd', 'username' => 'wwwww'],
            ['uid' => 'ashdajsdad', 'username' => '22222211'],

        ]);
        //keyBy() 以指定键的值作为集合项目的键。如果几个数据项有相同的键，那在新集合中只显示最后一项
        $key = $collection->keyBy('uid');

        dump($key->all());

        $keyed = $collection->keyBy(function ($item) {
            return strtoupper($item['username']);
        });
        dump($keyed->all());
        //keys() 返回所有的键
        dump($keyed->keys()->all());

        dump($collection);

        $last = $collection->last(function ($value, $key) {
            return $value['uid'] === 'ashdajsdad';
        });

        dump($last);

        $last_s = $collection->last();

        dump($last_s);
        //map()   对值做修改
        $map = $collection->map(function ($item, $key) {
            return $item['username'] . 'syc';
        });

        dump($map->all());
        // mapWithKeys()  返回自定义键值对
        $key_value = $collection->mapWithKeys(function ($item) {
            return [$item['uid'] => $item['username']];
        });

        dump($key_value->all());

        $max = $collection->max('uid');

        dump($max);

        /*
         *array:4 [▼
         *    0 => array:2 [▶]
         *    1 => array:2 [▶]
         *    2 => array:2 [▶]
         *    "php" => "most"
         *  ]
         */
        $merge = $collection->merge(['php' => 'most']);

        dump($merge->all());

        $min = $collection->min('username');

        dump($min);
    }

    public function only()
    {
        $collection = collect([
            'name' => 'syc',
            'sex' => 'man',
            'age' => 12,
        ]);
        //only 返回指定键的值
        $filtered = $collection->only('name', 'age');

        dump($filtered->all());

        //pipe 回调数据
        $piped = $collection->pipe(function ($collection) {
            return $collection->max();
        });

        dump($piped);

        //pluck 多维数组
        $pluck = $collection->pluck('name');

        dump($pluck->all());
        //pop 移除最后一个单元
        dump($collection->pop());

        dump($collection->all());
        //prepend 新增一个键值作为第一个元素
        dump($collection->prepend(20, 'age')->all());

        //pull
        dump($collection->pull('name'));

        dump($collection->all());
        //push 压入单元 最后一个单元
        dump($collection->push('syc')->all());

        //put 设置一个键值对
        dump($collection->put('name', 'syc')->all());

        //随机返回一个元素
        dump($collection->random());
        //方法将集合缩减到单个数值，该方法会将每次迭代的结果传入到下一次迭代：
        $string = $collection->reduce(function ($carry, $item) {
            return $carry . $item;
        });
        dump($string);

        //移除通过测试的元素
        $filtered = $collection->reject(function ($value) {
            return $value === 'syc';
        });
        dump($filtered->all());

        //按原顺序倒序排列集合
        $reversed = collect([1, 2, 3, 6, 5, 4])->reverse();

        dump($reversed->all());
        //查找值返回键，查不到返回false
        dump($collection->search('man'));
        //移除第一个元素
        dump($collection->shift());

        dump($collection->all());

        //打乱集合的顺序
        dump(collect([1, 2, 3, 4, 5, 6, 7, 8, 9])->shuffle()->all());

        //从指定索引分割集合
        dump(collect([1, 2, 3, 4, 5, 6, 7, 8, 9])->slice(2, 6)->all());

        dump(collect([1, 3, 2, 4, 9, 8, 6, 4, 6, 8])->sort()->all());

        $collection = collect([
            ['id' => 1, 'name' => 'syc'],
            ['id' => 2, 'name' => 'abc'],
            ['id' => 3, 'name' => 'sov'],
            ['id' => 4, 'name' => 'ylc'],
        ]);
        //sortByDesc 逆序
        $sorted = $collection->sortBy('id');
        dump($sorted->all());

        dump($collection->splice(2, 2, [['id' => 5, 'name' => 'nicia'],
            ['id' => 6, 'name' => 'caimao'],]));

        dump($collection->all());

        //sum
        dump(collect([1, 3, 4, 5, 5, 6,])->sum());

        dump($collection->sum('id'));

        dump(collect([1, 3, 4, 5, 5, 6, 7, 8, 9, 10])->take(2)->all());

        $collect = collect([1, 3, 4, 5, 5, 6, 7, 8, 9, 10])->transform(function ($item) {
            return $item * 100;
        });

        dump($collect->all());
        //合并数组到集合，保留集合中的元素
        dump(collect([1 => ['q'], 2 => ['a']])->union([3 => ['c'], 1 => ['g']])->all());
        //去除重复的值，多维数组去除指定key的重复值 也可使用回调函数处理
        //values 返回键连续的key
        dump(collect([1, 2, 3, 2, 3, 1, 4, 55, 6, 7])->unique()->values()->all());

        // 指定键值，过滤数据
        $collection = collect([
            ['product' => 'Desk', 'price' => 200],
            ['product' => 'Chair', 'price' => 100],
            ['product' => 'Bookcase', 'price' => 150],
            ['product' => 'Door', 'price' => '100'],
        ]);

        $filtered = $collection->where('price', 100);

        dump($filtered->all());
        //whereStrict 和where一样的形式，但是会检查数据类型
        $filtered = $collection->whereStrict('price', 100);

        dump($filtered->all());

        //whereIn
        $filtered = $collection->whereIn('price', [100, 150]);

        dump($filtered->all());
        //zip
        $collection = collect(['Chair', 'Desk']);

        $zipped = $collection->zip([100, 200]);

        $zipped->all();

    }


}