<?php
/**
 * description
 *
 * @category   Learn
 * @package    PSR
 * @subpackage Documentation\API
 * @author     yichaosong  <songyichao@mioji.com>
 * @license    GPL https://mioji.com
 * @link       https://mioji.com
 */

namespace Tests\Unit;


use App\Http\Controllers\CollectionController;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    public function testCreate()
    {
        $collection = new CollectionController();
        $old_data = ['syc', '', null];
        $data = $collection->create($old_data);
        $this->assertEquals(['SYC'], $data);
    }

    public function testValidateLogin()
    {
        $collection = new CollectionController();
        $uid = 'syc';
        $_SESSION['uid'] = $uid;
        $data = $collection->validateLogin($uid);
        $this->assertEquals(true, $data);
    }

    public function testCreate1()
    {
        $collection = new CollectionController();
        $old_data = ['syc', '', null];
        $data = $collection->create($old_data);
        $this->assertEquals(['syc'], $data);
    }
}