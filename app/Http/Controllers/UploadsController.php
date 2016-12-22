<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Mail;
use Cache;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Image;

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
		//		Mail::raw('邮件内容', function ($message) {
		//			$message->from('songyichao74@163.com', 'syc');
		//			$message->subject('测试邮件');
		//			$message->to('songyichao@mioji.com');
		//		});
		Mail::send('upload.mail',
			['name' => 'word'],
			function ($message) {
				$message->to('songyichao@mioji.com');
			});
	}
	
	//
	public function upload(Request $request)
	{
		if ($request->isMethod('POST')) {
			$file = $request->file('file');
			if ($file->isValid()) {
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
				dd($file_name);
			}
			
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
		$paragraph_style = ['align' => 'center', 'spacing' => 15];
		$paragraph_style_2 = ['align' => 'left', 'spacing' => 12];
		$section->addText('意大利五日游利五日游风情迷人浪漫极致', $font_style_40, $paragraph_style);
		$section->addTextBreak();
		$font_style_15 = ['color' => '#000', 'size' => 15, 'bold' => true];
		$paragraph_style = ['align' => 'center', 'spacing' => 15];
		$section->addText('罗马 - 蒙特卡蒂尼 - 佛罗伦萨 - 威尼斯 - 意大利小城 - 罗马城市A - 城市B - 城市C - 城市D- 城市E -城市F',
			$font_style_15, $paragraph_style);
		$section->addTextBreak();
		$section->addText('2016.12.08－12.12', $font_style_20, $paragraph_style);
		$section->addTextBreak(2);
		$image_style = [
			'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
			'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
			'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
			'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
			'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
			'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
			'marginLeft' => 0,
			'left' => 0,
			'width' => 595,
			'height' => 320,
			'wrappingStyle' => 'infront',
			'align' => 'start'
		];
		$image_style_left = ['width' => 197, 'height' => 128, 'align' => 'left'];
		$image_style_right = ['width' => 197, 'height' => 128, 'align' => 'right'];
		$cell_row_continue = ['vMerge' => 'continue'];
		$cell_v_centered = ['valign' => 'center'];
		$cell_col_span = ['gridSpan' => 2, 'valign' => 'center'];
		$cell_col_span_3 = ['gridSpan' => 3, 'valign' => 'center'];
		$cell_row_span = ['vMerge' => 'restart', 'valign' => 'center'];
		$cell_img_left = ['borderRightColor' => '#FFFFFF', 'borderRightSize' => 0, 'valign' => 'center'];
		$cell_img_right = ['borderLeftColor' => '#FFFFFF', 'borderLeftSize' => 0, 'valign' => 'center'];
		$section->addImage(base_path() . '/storage/app/public/E16291EFB6867F12B39CD0EE349C5802.jpg', $image_style);
		$section->addPageBreak();
		$section->addTextBreak();
		$section->addText('一、行程安排', $font_style_15, $paragraph_style_2);
		$section->addImage(base_path() . '/storage/app/public/CA93B25649CCC41FB39BBD2414EC72D1.jpg',
			['width' => '482', 'align' => 'center']);
		$section->addText('A.罗马   B.蒙特卡蒂尼  C.佛罗伦萨  D.威尼斯  E.意大利小城  F.米兰', $font_style_12, $paragraph_style_2);
		$section->addTextBreak();
		$table_style = [
			'width' => '9639',
			'borderSize' => 6,
			'cellMargin' => 50
		];
		$table = $section->addTable($table_style);
		$table->addRow();
		$table->addCell('1134')->addText('日期', $font_style_12, $paragraph_style);
		$table->addCell('8505', $cell_col_span)->addText('行程描述', $font_style_12, $paragraph_style);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D112.8周二', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('北京 - ️️罗马', $font_style_12, $paragraph_style_2);
		$cell->addText('从北京乘坐飞机前往罗马，开启美好的旅程（参考交通: MU559  PEKFCO  用时：2小时55分）本日将在罗马入住休息。',
			$font_style_12, $paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('含餐：-', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('参考酒店：罗马酒店（五星）', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D212.9周三', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('罗马  蒙特卡蒂尼', $font_style_12, $paragraph_style_2);
		$cell->addText('早餐后，上午将游览罗马的梵蒂冈博物馆3小时30分、西斯廷礼拜堂 1小时15分、圣彼得大教堂2小时45分。下午将前往走进圣彼得广场3小时。随后从罗马乘火车前往蒙特卡蒂尼（参考交通: MU559  PEKFCO  用时：2小时55分）
本日将在蒙特卡蒂尼入住休息。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('本日重点景点介绍：',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('1、梵蒂冈博物馆：梵蒂冈博物馆位于梵蒂冈城内，由罗马梵蒂冈大道（Viale Vaticano）可达。梵蒂冈博物馆是世界上最伟大的博物馆之一，其中的藏品是多个世纪以来罗马天主教会收集、积累的成果。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('2、西斯廷礼拜堂：是一座位于梵蒂冈宗座宫殿内的天主教小堂，紧邻圣伯多禄大殿，以米开朗基罗所绘《创世纪》穹顶画，及壁画《最后的审判》而闻名。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('4252.5', $cell_img_left)->addImage(base_path() . '/storage/app/public/15E89353488417E57EC29449680FE5BE.jpg', $image_style_left);
		$table->addCell('4252.5', $cell_img_right)->addImage(base_path() . '/storage/app/public/95D04E84A2A2748363070AF86ED0545F.jpg', $image_style_right);
		$table->addRow();
		$table->addCell(null, $cell_row_continue);
		$table->addCell('2551.5')->addText('含餐：早 午 晚', $font_style_12, $paragraph_style_2);
		$table->addCell('5953.5')->addText('参考酒店：蒙特卡蒂尼酒店（五星）', $font_style_12, $paragraph_style_2);
		$table->addRow();
		$table->addCell('1134', $cell_row_span)->addText('D312.10周四', $font_style_12, $paragraph_style);
		$cell = $table->addCell('8505', $cell_col_span);
		$cell->addTextBreak();
		$cell->addText('蒙特卡蒂尼 - 佛罗伦萨 - 威尼斯 - 意大利小城', $font_style_12, $paragraph_style_2);
		$cell->addText('早餐后，上午将游览蒙特卡蒂尼的罗马斗兽场3小时45分钟、君士坦丁凯旋门30分钟。随后乘车前往佛罗伦萨，游览佛罗伦萨的真理之口 15分钟，下午将前往古罗马广场2小时15分钟。随后乘车从佛罗伦萨前往威尼斯，游览威尼斯的威尼斯广场1小时15分钟、图拉真柱30分钟，随后乘车前往意大利小城。
本日将在意大利小城入住休息。',
			$font_style_12,
			$paragraph_style_2);
		$cell->addTextBreak();
		$cell->addText('本日重点景点介绍：',
			$font_style_12,
			$paragraph_style_2);
		$cell->addText('1、罗马斗兽场：又译作罗马斗兽场、罗马大角斗场、罗马圆形竞技场、科洛西姆或哥罗塞姆；原名弗莱文圆形剧场，是古罗马时期最大的圆形角斗场，建于公元72年-82年间，现仅存遗迹位于现今意大利罗马市的中心。',
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
		$section->addText('备注：', $font_style_12, $paragraph_style_2);
		$section->addText('1.行程中所列航班号及时间仅供参考，将根据实际情况做出合理的调整；', $font_style_12, $paragraph_style_2);
		$section->addText('2.请您在境外期间遵守当地的法律法规，以及注意自己的人身安全；', $font_style_12, $paragraph_style_2);
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
		$cell->addText('蒙特卡蒂尼酒店（五星）', $font_style_12, $paragraph_style_2);
		$cell->addText('意大利小城酒店（五星）', $font_style_12, $paragraph_style_2);
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
		$file_name = $operate_dir . 'helloWord.doc';
		ob_end_clean();
		ob_start();
		$obj_writer = IOFactory::createWriter($php_word, 'Word2007');
		$obj_writer->save($file_name);
		header('Content-type: application/x-doc');
		header('Content-Disposition: attachment; filename=helloWord.doc');
		header('Content-Length: ' . filesize($file_name));
		readfile($file_name);
	}
}
