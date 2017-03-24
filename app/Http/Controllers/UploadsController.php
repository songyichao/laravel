<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Image;
use songyichao\kscdn\Ks3Client;

class UploadsController extends Controller
{
	public function error()
	{
		Log::info('这是一个info级别的日志');
		//		Log::warning('这是一个warning级别的日志');
		//		Log::info('这是一个info级别的日志');
	}
	
	public function cache1()
	{
		//		Cache::put('key1', 'val1', 10);
		//		dd(Cache::add('key1', 'val1', 10));
		//		dd(Cache::forever('key3','val3'));
		if (Cache::has('key1')) {
			dd(Cache::get('key1'));
		}
	}
	
	public function cache2()
	{
		//		dd(Cache::get('key3'));
		//		dd(Cache::pull('key3'));
		dd(Cache::forget('key1'));
	}
	
	public function mail()
	{
		$res = Mail::raw('邮件内容', function ($message) {
			$message->from('songyichao74@163.com', 'syc');
			$message->subject('测试邮件');
			$message->to(['songyichao@mioji.com', 'songyichao74@qq.com', 'songyichao74@sina.cn']);
		});
		dd($res);
		//				Mail::send('upload.mail',
		//					['name' => 'word'],
		//					function ($message) {
		//						$message->to('songyichao@mioji.com');
		//					});
		//
		//		$strParam = 'qwe))((&&((&*()"   {:}:we!#$!$^&*$*(&^%&*(*!$&^#';
		//		$regex = "/\/|\~|\!|\"|\ |\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
		//		dd(preg_replace($regex, "", $strParam));
		//		$str = 'Win32sdsd';
		//		dd(substr($str, 0, 3));
		$str = '<11月1日-12月24日><周2-周7><09:00-17:30><>|<4月1日-4月30日><周2-周7><09:00-18:30><SURE>';
		dd(strpos($str, 'SURE'));
	}
	
	//
	public function upload(Request $request)
	{
		if ($request->isMethod('POST')) {
			$file = $request->file('file');
				$original_name = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension();
				$type = $file->getClientMimeType();
				//临时绝对目录
				$real_path = $file->getRealPath();
				$file_name = time() . '_' . uniqid() . '.' . $extension;
				$file_name = base_path() . '/storage/app/uploads/' . $file_name;
				$file = fopen($file_name, 'w');
				fwrite($file, file_get_contents($real_path));
				fclose($file);
				//				$path = $file->move();
				//				$res = Storage::disk('uploads')->put($file_name, file_get_contents($real_path));
				
				
				$bucket = 'miojio.test';
				$client = new Ks3Client(config('params.ks3_AK'), config('params.ks3_SK'));
				
				$content = fopen($file, "r");
				$args_put = array(
					"Bucket" => $bucket,
					"Key" => $file_name, //文件名
					"Content" => array(
						"content" => $content,
						"seek_position" => 0
					),
					"ACL" => "public-read",
					"ObjectMeta" => array(
						//"Cache-Control" => 'no-cache',
						//'Content-Length' => '1024*1024*2014',
						"Content-Type" => mime_content_type($file),
					),
				);
				
				$re = $client->putObjectByFile($args_put);
				
				if (isset($re['ETag'])) {
					file_exists($file) ? unlink($file) : false;  //金山云上传成功，删除本地图片
					
					$cdn = config('params.cdn_addr');
					$data = [
						'url' => 'http://' . $bucket . '.' . $cdn . '/' . $file_name,
					];
					
					//上传文件成功后做记录
					$save_data = [
						'ptid' => $record_data['ptid'] ?? '',
						'uid' => $record_data['uid'] ?? '',
						'tid' => $record_data['tid'] ?? '',
						'url' => $data['url'],
						'type' => $record_data['type'] ?? 0,
					];
					(new Ks3UploadRecord())->saveRecord($save_data);
					
					
					return array_merge(Error::$normal, ['data' => $data]);
					
				} else {
					return Error::getToolsErrors(11003);
				}
				dd($file_name);
			
			
		}
		return view('upload.upload');
	}
	
	public function word()
	{
		$operate_dir = base_path() . '/storage/app/word/';
		
		if (!is_dir($operate_dir)) {
			@mkdir($operate_dir, 0777, true);
		}
		
		$php_word = new PhpWord();
		$php_word->setDefaultFontName('微软雅黑');
		$php_word->setDefaultFontSize(12);
		$section = $php_word->addSection();
		$section->addTextBreak();
		$font_style_40 = ['color' => '#000', 'size' => 40, 'bold' => true];
		$font_style_20 = ['color' => '#000', 'size' => 20, 'bold' => true];
		$font_style_12 = ['color' => '#000', 'size' => 12, 'bold' => true];
		$font_style_12_blue = ['color' => '#0061f3', 'size' => 12, 'bold' => true];
		$paragraph_style = ['align' => 'center', 'spacing' => 10];
		$paragraph_style_2 = ['align' => 'left', 'spacing' => 20];
		$font_style_15 = ['color' => '#000', 'size' => 15, 'bold' => true];
		$image_style_left = ['positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,];
		$image_style_right = ['positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,];
		$cell_row_continue = ['vMerge' => 'continue'];
		$cell_v_centered = ['valign' => 'center'];
		$cell_col_span = ['gridSpan' => 2, 'valign' => 'center'];
		$cell_col_span_3 = ['gridSpan' => 3, 'valign' => 'center'];
		$cell_row_span = ['vMerge' => 'restart', 'valign' => 'center'];
		$cell_img_left = ['borderRightColor' => '#FFFFFF', 'borderLeftColor' => '#FFFFFF', 'borderRightSize' => 0, 'borderLeftSize' => 0, 'valign' => 'center'];
		$cell_img_right = ['borderLeftColor' => '#FFFFFF', 'borderRightColor' => '#FFFFFF', 'borderLeftSize' => 0, 'valign' => 'center'];
		$table_style = [
			'width' => '9639',
			'borderSize' => 6,
			'cellMargin' => 50
		];
		$image_style = [
			'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
			'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
			'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
			'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
		];
		$section->addText('意大利五日游利五意大利五日游利五意大利', $font_style_40, $paragraph_style);
		$section->addTextBreak();
		$section->addText('罗马 - 蒙特卡蒂尼 - 佛罗伦萨 - 蒙特卡蒂尼 - 佛罗伦萨 - 蒙特卡蒂尼 - 佛罗伦萨',
			$font_style_15, $paragraph_style);
		$section->addTextBreak();
		$section->addText('2016.12.08－12.12', $font_style_20, $paragraph_style);
		$section->addTextBreak(2);
		
		
		$section->addImage('http://mioji-mobilepic.cdn.mioji.com/10001_1.jpg@base@tag=imgScale&m=1&r=0&c=1&q=85&w=600&h=450&rotate=0', $image_style);
		$section->addPageBreak();
		$section->addTextBreak();
		$section->addText('从北京乘坐飞机前往罗马(参考交通：KL4302 PEKAMS  / KL3405 AMSFCO  用时：15小时40分)，开启美好的旅程。上午将游览罗马的多利亚潘菲利别墅2小时30分。', $font_style_15, $paragraph_style_2);
		$section->addImage(base_path() . '/storage/app/public/CA93B25649CCC41FB39BBD2414EC72D1.jpg',
			['positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
				'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
				'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
				'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
				//				'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
			]);
		$section->addText('1、多里亚·潘菲利别墅（Villa Doria Pamphili 或Pamphilj）在意大利的首都罗马，位于贾尼科洛山，就在罗马古城墙的圣庞加爵门外，是古代的奥雷利亚大道的起点，是一座17世纪别墅，拥有今天罗马最大的风景园林，是罗马市民休闲娱乐之地。这座别墅最初是潘菲利家族的别墅，1760年该家族绝嗣，1763年，教宗克雷芒十三世将这座园林授予多里亚亲王，从此称为多里亚潘菲利别墅。', $font_style_12, $paragraph_style_2);
		$section->addTextBreak();
		$table = $section->addTable($table_style);
		$table->addRow();
		$table->addCell('1134')->addText('亚尔维米纳勒山客栈及酒店(3星)', $font_style_12, $paragraph_style);
		$table->addCell('8505', $cell_col_span)->addText('行程描述', $font_style_12, $paragraph_style);
		$table->addRow();
		$cell = $table->addCell('1134', $cell_row_span);
		$cell->addText('D1', $font_style_12, $paragraph_style);
		$cell->addText('12.8', $font_style_12, $paragraph_style);
		$cell->addText('周二', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('北京 - ️️罗马', $font_style_12, $paragraph_style_2);
		$cell->addText('1、人民广场（Piazza del Popolo）位于意大利首都罗马，此处的人民门即罗马城墙的弗拉米尼亚门（北门）。广场是弗拉米尼亚路的起点，该路通往弗拉米尼亚（今里米尼），是通往北方最重要的道路。在铁路时代以前，此处是旅行者抵达罗马时首先看到的景色。数百年间，人民广场是公开执行死刑的地方，最后一次是在1826年。人民广场上有两座极为相似的教堂比邻而立：奇迹圣母堂（Santa Maria dei Miracoli）和圣山圣母堂（Santa Maria in Montesanto），北面不远有人民圣母教堂（Santa Maria del Popolo）。',
			$font_style_12, $paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('2、君士坦丁凯旋门（Arco di Costantino）是位于意大利罗马的一座凯旋门，位于罗马斗兽场与帕拉蒂诺山之间。君士坦丁凯旋门是为了纪念君士但丁一世在公元312年10月28日的米尔维安大桥战役中大获全胜而建立的，是罗马现存的凯旋门中最新的一座。君士坦丁凯旋门长25.7米，宽7.4米，高21米，因为建造仓促，装饰部分多由之前历任皇帝的纪念建筑上拆卸组装而来，只有左右两个小拱门上方的浮雕是为君士但丁凯旋门而创作的。', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('1、真理之口（Bocca della Verità）位于意大利城市罗马的希腊圣母堂（Basilica di Santa Maria in Cosmedin）的门廊下，是一个有着类似人脸雕塑的圆形大理石雕塑。真理之口得名于一个从中世纪开始流传的传说：如果一个撒谎的人把手伸进真理之口，他的手就会被咬住无法拔出。在电影《罗马假日》中公主和记者“测谎”的著名桥段正是利用这个传说，从而也让真理之口名声大噪。', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D212.9周三', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText("21321312", $font_style_12, $paragraph_style_2);
		$cell->addText(str_replace('&', 'and', 'B&B Hotel Milano Sant\'Ambrogio(3星)'),
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('从罗马乘坐飞机前往北京(参考交通：AF1405 FCOCDG  / AF382 CDGPEK  用时：14小时35分)，早餐后，上午将游览罗马的古亚辟大道1小时30分。随后整理行李，乘坐飞机返回[北京]，结束16日美妙之旅：',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('1、亚壁古道（Via Appi）是古罗马时期一条把罗马及意大利东南部阿普利亚的港口布林迪西连接起来的古道。罗马军队的成功，道路发展起到了举足轻重的作用。道路的使用在战前的准备和战争期间的物资装备的补给，而亚壁古道就是这样一条战略要道。从公元前350年开始，以后的很多年里亚壁古道为罗马帝国的扩大发挥了重要作用。亚壁古道古时称Regina Viarum（路之女王），斯巴达克斯起义在前71年被扑灭后，被俘的6000个奴隶就是在这里被钉在十字架上死去的。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('2、拉特朗宫（Palazzo Laterano）位于意大利罗马东南部的拉特朗圣若望广场，毗邻罗马的主教座堂拉特朗圣若望大殿，自4世纪以后的一千年中一直是教宗的主要驻地，现在仍是宗座宫殿之一。目前开设梵蒂冈历史博物馆，介绍该国的历史。拉特朗宫也是罗马牧区办公室的所在地，设有罗马代理枢机的官邸。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('4252.5', $cell_img_left)->addImage(base_path() . '/storage/app/public/15E89353488417E57EC29449680FE5BE.jpg', $image_style_left);
		$table->addCell('4252.5', $cell_img_left)->addImage(base_path() . '/storage/app/public/95D04E84A2A2748363070AF86ED0545F.jpg', $image_style_right);
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('早餐后，上午将游览罗马的解放史博物馆1小时30分、圣阶和圣中圣礼拜堂1小时30分，下午将前往拉特兰圣乔凡尼大教堂1小时45分、拉特朗宫2小时、罗马公墓1小时30分。', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('2、凯撒神庙（Aedes Divi Iulii 或 Templum Divi Iulii）始建于公元前42年，位于古罗马广场主广场的东侧。在罗马元老院追封恺撒为神之后，凯撒神庙同时也被称为彗星神庙(Temple of the Comet Star)，原因是屋大维在凯撒被刺杀后四个月，在公元前44年7月也是凯撒的诞生月，在罗马举办了纪念凯撒的竞技运动会。在竞技运动会举行时有颗明亮的大彗星连续7日出现在罗马夜空中，罗马人相信这是凯撒的灵魂升上天成为神的证明，所以在神庙内所供奉的是一个额上有一颗星的凯撒像。', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('1.罗马 ', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('1、奥斯提亚安提卡（Ostia Antica）位于意大利中部拉齐奥大区罗马市奥斯提亚的一座古罗马时期的港湾都市遗迹。由于砂石堆积，现在奥斯提亚安提卡距离海岸线已有3公里。在神话记载中，这里在公元前7世纪时就已经有城市。但按照实际考古记录，这座城市的历史可以追溯至公元前4世纪初期。在公元前3世纪至2世纪时，这里曾是罗马海军的主要据点。2世纪时，城市发展达到鼎盛时期。但由于砂石堆积导致港口淤塞，城市人口逐渐减少。罗马帝国末期，奥斯提亚安提卡被废弃。虽然位置较为偏僻，但是却有〝小庞贝城〞之称，可以看到浴场、神庙等遗迹。', $font_style_12, $paragraph_style_2);
		$cell->addText('1、波格赛美术馆（Museo e Galleria Borghese）是位于意大利罗马波格赛别墅中的一座美术馆，其前身是西皮奥内·波格赛枢机大臣的别墅。美术馆主要收藏意大利文艺复兴和巴洛克美术藏品，包括了卡拉瓦乔、拉斐尔、堤香等人的作品。美术馆于1613年开始施工，1616年完工（也有资料称完工于1621年），为巴洛克风格。1903年之后，这里成为意大利的国立美术馆。波格赛美术馆采取预约制，所有游客必须经过提前预约才能在规定的时间段内进入美术馆参观。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('2、阿达别墅（Villa Ada）位于意大利罗马的东北部，是整个城市最大的一个公园，面积182公顷。在十九世纪后半叶，这片树木繁茂的地方属于意大利的萨伏依王室，包含皇家别墅。1878年这一地区归属瑞士的 Tellfner伯爵，他以妻子阿达的名字命名。1904年，王室收回这片土地，但没有改变名称。他们保有这块地方，直到1946年。截至2009年，公园包括公共部分比私有领地。公共区域由罗马市议会管理，私有领地由埃及大使馆管理，虽然市议会已正式要求管理整个区域。私有领地经常有警察或军队人员巡逻。公园的公共部分比私有区大得多。它包含一个人工湖，许多树木，包括石松、圣栎, 月桂和非常罕见的水杉，于1940年从西藏进口。公园免费入内。人们可以租皮划艇，自行车，或骑马，园内还有一个大型游泳池。自1994年以来，公园在夏天举办世界音乐音乐节和Roma incontra il mondo（罗马与世界相会）音乐节，反对种族主义，战争和死刑。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('1、真理之口（Bocca della Verità）位于意大利城市罗马的希腊圣母堂（Basilica di Santa Maria in Cosmedin）的门廊下，是一个有着类似人脸雕塑的圆形大理石雕塑。真理之口得名于一个从中世纪开始流传的传说：如果一个撒谎的人把手伸进真理之口，他的手就会被咬住无法拔出。在电影《罗马假日》中公主和记者“测谎”的著名桥段正是利用这个传说，从而也让真理之口名声大噪。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('2、君士坦丁凯旋门：是位于罗马的一座凯旋门，位于罗马竞技场与帕拉蒂尼山之间。君士坦丁凯旋门是为了纪念君士坦丁一世于312年10月28日的米尔维安大桥战役中大获全胜而建立的。君士坦丁凯旋门也是罗马现存的凯旋门中最新的一座。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('4252.5', $cell_img_left)->addImage(base_path() . '/storage/app/public/15E89353488417E57EC29449680FE5BE.jpg', $image_style_left);
		$table->addCell('4252.5', $cell_img_right)->addImage(base_path() . '/storage/app/public/95D04E84A2A2748363070AF86ED0545F.jpg', $image_style_right);
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('含餐：早 午 ', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('参考酒店：意大利小城酒店（五星）', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D412.11周五', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('意大利小城 - 罗马 - 北京', $font_style_12, $paragraph_style_2);
		$cell->addText('乘车从意大利小城前往罗马，上午将游览罗马的人民广场30分钟、西班牙广场 2小时45分钟，随后整理行李，乘飞机返回北京，结束5日美妙之旅。
参考航班:  AY880     CDGHEL     （飞行时间2小时55分）',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('本日重点景点介绍：',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('1、人民广场：是罗马的一个广场，得名于广场东北角圣玛利亚教堂后面的白杨树。此处的人民门即罗马城墙的弗拉米尼亚门。人民广场是弗拉米尼亚路的起点，该路通往弗拉米尼亚，是通往北方最重要的道路。在铁路时代以前，此处是旅行者抵达罗马时首先看到的景色。数百年间，人民广场是公开执行死刑的地方，最后一次是在1826年。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('2、西班牙广场：是位在意大利罗马的广场，旁有罗马地铁的同名车站。山上天主圣三教堂就位在与西班牙广场相接的西班牙阶梯顶端。西班牙广场也曾经出现在电影《罗马假期》的场景中。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('4252.5', $cell_img_left)->addImage(base_path() . '/storage/app/public/15E89353488417E57EC29449680FE5BE.jpg', $image_style_left);
		$table->addCell('4252.5', $cell_img_right)->addImage(base_path() . '/storage/app/public/95D04E84A2A2748363070AF86ED0545F.jpg', $image_style_right);
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('含餐：早 午 ', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('参考酒店：', $font_style_12, $paragraph_style_2);
		$section->addPageBreak();
		$section->addTextBreak();
		$section->addText('二、报价信息', $font_style_15, $paragraph_style_2);
		$table = $section->addTable($table_style);
		$table->addRow();
		$table->addCell('1984.5', $cell_v_centered)->addText('团名', $font_style_12, $paragraph_style_2);
		$table->addCell('6804', $cell_col_span_3)->addText('小红帽旅游团', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5', $cell_v_centered)->addText('联系人', $font_style_12, $paragraph_style_2);
		$table->addCell('2835', $cell_v_centered)->addText('刘女士', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('出行人数', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5', $cell_v_centered)->addText('游玩日期', $font_style_12, $paragraph_style_2);
		$table->addCell('2835', $cell_v_centered)->addText('2016.12.8- 2016.12.11', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('天数', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('5天', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5', $cell_v_centered)->addText('报价', $font_style_12, $paragraph_style_2);
		$table->addCell('2835', $cell_v_centered)->addText('CNY 1800／人', $font_style_12_blue, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('报价日期', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('2016.12.12', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5', $cell_v_centered)->addText('报价人', $font_style_12, $paragraph_style_2);
		$table->addCell('2835', $cell_v_centered)->addText('Sam', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('电话', $font_style_12, $paragraph_style_2);
		$table->addCell('1984.5', $cell_v_centered)->addText('18610481353', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5')->addText('参考酒店', $font_style_12, $paragraph_style_2);
		$cell = $table->addCell('6804', $cell_col_span_3);
		$cell->addText(str_replace('&', 'and', '豪特维尔歌剧院酒店(3星)<w:br />InterCityHotel Berlin Ostbahnhof(3星)<w:br />B&B Hotel Milano Sant\'Ambrogio(3星)'), $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5')->addText('费用包含', $font_style_12, $paragraph_style_2);
		$table->addCell('6804', $cell_col_span_3)->addText('@萌萌', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5')->addText('费用不包含', $font_style_12, $paragraph_style_2);
		$table->addCell('6804', $cell_col_span_3)->addText('@萌萌', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1984.5')->addText('备注', $font_style_12, $paragraph_style_2);
		$table->addCell('6804', $cell_col_span_3)->addText('', $font_style_12, $paragraph_style_2);
		$section->addTextBreak();
		$section->addText('三、温馨提示', $font_style_15, $paragraph_style_2);
		$section->addText('1、当您从欧洲离境时，一定检查海关是否给您的护照盖了清晰的离境章，它是您已经回到中国的唯一凭证。如果没有盖章或者章不清晰无法辨认将会导致使馆要求您面试销签，由此造成不必要的损失，非常抱歉只能由本人承担！ 请您谅解的同时也请您自己务必仔细留意！
', $font_style_12, $paragraph_style_2);
		$section->addText('2、 行程中所列航班号及时间仅供参考，将根据实际情况做出合理的调整；', $font_style_12, $paragraph_style_2);
		$section->addText('3、欧洲同北京时间时差：夏季六小时；冬季七小时（个别国家不同地区也会有时差，均以当地到达时间为准）；', $font_style_12, $paragraph_style_2);
		$section->addText('4、行程中所注明的城市间距离，参照境外地图，仅供参考，视当地交通状况进行调整；', $font_style_12, $paragraph_style_2);
		$section->addText('5、 根据欧共体法律规定，导游和司机每天工作时间不得超过10小时；', $font_style_12, $paragraph_style_2);
		$section->addText('6、请您在境外期间遵守当地的法律法规，以及注意自己的人身安全；', $font_style_12, $paragraph_style_2);
		$section->addText('7、 此参考行程和旅游费用，我公司将根据参团人数、航班、签证及目的地国临时变化保留调整的权利；', $font_style_12, $paragraph_style_2);
		$section->addText('8、 依照旅游业现行作业规定，本公司有权依据最终出团人数情况，调整房间分房情况。', $font_style_12, $paragraph_style_2);
		$section->addText('9、 由于欧洲各国国情不同，您在欧洲旅游时除了准备信用卡及银联卡外，请尽量多准备一些现金。欧洲有些商 店不能刷卡只接受现金。另外提醒您如果您携带信用卡，请在国内确认好已经激活可以在境外使用！', $font_style_12, $paragraph_style_2);
		$section->addText('10、购物属于个人行为，本旅行社不指定具体购物地点，请您在境外购物时务必注意以下几点', $font_style_12, $paragraph_style_2);
		$section->addText('请您在购买商品时，仔细检查商品质量，避免退换货的可能性。', $font_style_12, $paragraph_style_2);
		$section->addText('退税是欧盟对非欧盟游客在欧洲购物的优惠政策，整个退税手续及流程均由欧洲国家控制，经常会出现退税不     成功、税单邮递过程中丢失导致无法退税等问题。', $font_style_12, $paragraph_style_2);
		$section->addText('导游会协助您讲解如何办理退税手续、指导税单填写。但是因为个人问题（如没有仔细听讲、没有按照流程操作、没有按照流程邮寄税单）或者客观原因（如遇到海关退税部门临时休息、海关临时更改流程、税单在邮寄过程中发生问题商家没有收到税单等）在退税过程中出现错误，导致您被扣款、无法退钱、退税金额有所出入等任何情况，旅行社和导游都不能承担您的任何损失，请您理解。
', $font_style_12, $paragraph_style_2);
		$section->addText('11、欧洲主要流通货币为欧元，其他货币在使用或在欧洲兑换欧元时都会有汇率损失，建议您出国前兑换好所需欧元。最好参照您的行程来准备适量欧元（2000左右为宜）。如果您携带信用卡，请在国内确认好已经激活才可以在境外使用。
', $font_style_12, $paragraph_style_2);
		$file_name = $operate_dir . 'helloWord.docx';
		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $file_name . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		$obj_writer = IOFactory::createWriter($php_word, 'Word2007');
		$obj_writer->save("php://output");
	}
}