<?php
/**
 * description
 *
 * @category   Learn
 * @package    PSR
 * @subpackage Documentation\API
 * @author     yichaosong  <songyichao@gmail.com>
 * @license    GPL https://songyichao.com
 * @link       https://songyichao.com
 */

namespace Tests\Unit;

class ExampleSeleniumTest extends \PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setBrowser(env('BROWSER'));
        $this->setBrowserUrl('http://' . env('WEBSERVER_URL') . ':' . env('WEBSERVER_PORT'));
        $this->setHost(env('SELENIUM_URL'));
        $this->setPort((int)env('SELENIUM_PORT'));
    }

    public function testTitle()
    {
        $this->url('/');
        $this->assertEquals('百度一下，你就知道', $this->title());
        // 搜索"Hello"
        $keywordInput = $this->byId('kw');
        $this->assertNotEmpty($keywordInput);
        $keywordInput->value('Hello');

        $searchBtn = $this->byId('su');
        $this->assertNotEmpty($searchBtn);

        $searchBtn->click();

        // 等待结果
//        $this->waitUntil(function () {
//            $this->alertIsPresent();
//        }, 10000);

        // 检查结果
        $keywordInput = $this->byId('kw');
        $this->assertNotEmpty($keywordInput);
        $this->assertEquals('Hello', $keywordInput->attribute('value'));

        // 为了演示，留个时间看看结果
        sleep(3);

        // 关闭浏览器
//        $this->();
    }
}