<?php

namespace app\Http\Controllers;


use function dd;
use Illuminate\Http\Request;
use function var_dump;

require(base_path() . '/extensions/imsdk_restapi-php-sdk/TimRestApi.php');

class TenxunyunController
{
	/**
	 * @param Request $request
	 */
	public function account(Request $request)
	{
		$sdkappid = 1400023296;
		$identifier = 'lilei';
		$private_key_path = base_path() . '/resources/keys/private_key';
		$signature = base_path() . '/extensions/imsdk/signature/linux-signature32';
		
		$api = new \TimRestAPI();
		$api->init($sdkappid, $identifier);
		
//		$ret = $api->generate_user_sig($identifier, '86400', $private_key_path, $signature);
//		var_dump($identifier);
//		dd($ret);
//		if ($ret === null) {
//			var_dump('-10');
//		}
		//		$ret = $api->group_create_group('Public', 'service', 'admin');
		//		dd($ret);
		//				$ret = $api->register_account('sysysy', 1, 'sss123456');
		//				dd($ret);
		$name = $request->get('name');
		$ret = $api->account_import($name, $name . '123', '');
		var_dump($ret);
		$ret = $api->group_add_group_member('@TGS#2JCIIILEY', $name, 1);
		dd($ret);
	}
	
	public function send(Request $request)
	{
		$sdkappid = 1400023296;
		$identifier = 'admin';
		$private_key_path = base_path() . '/resources/keys/private_key';
		$signature = base_path() . '/extensions/imsdk/signature/linux-signature32';
		
		$api = new \TimRestAPI();
		$api->init($sdkappid, $identifier);
		
		$ret = $api->generate_user_sig($identifier, '86400', $private_key_path, $signature);
		
		if ($ret === null) {
			var_dump('-10');
		}
		$from = $request->get('from');
		$to = $request->get('to');
		//		$message = $request->get('message');
		$message = [
			[
				'MsgType' => 'TIMCustomElem',
				'MsgContent' => [
					'type' => 1,
					'num' => 10,
					'data' => [
						'info' => [
							'uid' => 'uuuuuuuu',
							'ccy' => 'CNY',
							'stat' => 1,
						],
					],
				],
			],
		
		];
		//		$ret = $api->sns_friend_import($from, $to, 'sysysys');
		//		dd($ret);
		$ret = $api->openim_send_msg2($from, $to, $message);
		dd($ret);
	}
	
	public function get_profile()
	{
		$sdkappid = 1400023296;
		$identifier = 'admin';
		$private_key_path = base_path() . '/resources/keys/private_key';
		$signature = base_path() . '/extensions/imsdk/signature/linux-signature32';
		
		$api = new \TimRestAPI();
		$api->init($sdkappid, $identifier);
		
		$ret = $api->generate_user_sig($identifier, '86400', $private_key_path, $signature);
		
		if ($ret === null) {
			var_dump('-10');
		}
		$tag_list = [
			"Tag_Profile_IM_Nick",
			"Tag_Profile_IM_AllowType"
		];
		
		$ret = $api->profile_portrait_get2(['syc', 'lgc', 'yzb', 'ysj', 'lilei', 'lr'], $tag_list);
		foreach ($ret['UserProfileItem'] as $item) {
			$data[$item['To_Account']] = [
				'nick_name' => $item['ProfileItem'][0]['Value'],
			];
		}
		dd($data);
	}
	
	public function get()
	{
		
	}
}