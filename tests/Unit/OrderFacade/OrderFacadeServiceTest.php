<?php

namespace Tests\Unit\OrderFacade;

use Biz\BaseTestCase;
use Biz\OrderFacade\Product\CourseProduct;
use Biz\OrderFacade\Service\OrderFacadeService;
use Biz\OrderFacade\Product\ClassroomProduct;
use Biz\Accessor\AccessorInterface;

class OrderFacadeServiceTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->mockCurrency();
    }

    public function testCreate()
    {
        $biz = $this->getBiz();

        $this->mockBiz('Course:CourseService', array(
            array('functionName' => 'getCourse', 'returnValue' => array('id' => 1, 'title' => 'course name1', 'price' => 100, 'originPrice' => 200, 'courseSetId' => 1, 'status' => 'published', 'maxRate' => 0, 'buyable' => true)),
            array('functionName' => 'canJoinCourse', 'returnValue' => array('code' => AccessorInterface::SUCCESS)),
        ));
        $courseProduct = $biz['order.product.'.CourseProduct::TYPE];
        $courseProduct->init(array('targetId' => 1));

        $courseProduct->pickedDeducts = array(
            array('deduct_id' => 1, 'deduct_type' => 'rewardPoint', 'deduct_amount' => 20),
            array('deduct_id' => 2, 'deduct_type' => 'discount', 'deduct_amount' => 100),
        );

        $order = $this->getOrderFacadeService()->create($courseProduct);

        $this->assertEquals(80 * 100, $order['pay_amount']);
    }

    /**
     * @expectedException \Codeages\Biz\Framework\Service\Exception\ServiceException
     */
    public function testCheckOrderBeforePay()
    {
        $this->mockBiz('Order:OrderService', array(
           array('functionName' => 'getOrderBySn', 'returnValue' => array()),
        ));

        $this->getOrderFacadeService()->checkOrderBeforePay('12456', array());
    }

    public function testCreateCourseImportOrder()
    {
        $this->mockBiz('Course:CourseService', array(
            array('functionName' => 'getCourse', 'returnValue' => array('id' => 1, 'title' => 'course name1', 'price' => 1, 'originPrice' => 10, 'courseSetId' => 1, 'status' => 'published', 'maxRate' => 0)),
        ));

        $this->mockBiz('Course:MemberService', array(
            array('functionName' => 'becomeStudent', 'returnValue' => array('id' => 1, 'courseId' => 1, 'userId' => 10)),
            array('functionName' => 'isCourseStudent', 'returnValue' => false),
        ));
        $this->mockBiz('Course:CourseSetService', array(
            array('functionName' => 'getCourseSet', 'returnValue' => array('id' => 1, 'title' => 'course set name1', 'cover' => '')),
        ));

        $biz = $this->getBiz();
        $courseProduct = $biz['order.product.'.CourseProduct::TYPE];

        $courseProduct->init(array('targetId' => 1));
        $courseProduct->price = 10;

        $params = array(
            'created_reason' => '课程用户导入订单',
            'price_type' => 'CNY',
        );
        $order = $this->getOrderFacadeService()->createSpecialOrder($courseProduct, 10, $params);

        $this->assertEquals('paid', $order['status']);
        $this->assertArraySubset($params, $order);
    }

    public function testCreateClassroomImportOrder()
    {
        $this->mockBiz('Classroom:ClassroomService', array(
            array('functionName' => 'getClassroom', 'returnValue' => array('id' => 1, 'title' => 'classroom name1', 'price' => 10, 'middlePicture' => '', 'status' => 'published', 'maxRate' => 0, 'smallPicture' => '', 'largePicture' => '')),
            array('functionName' => 'isClassroomStudent', 'returnValue' => false),
            array('functionName' => 'becomeStudent', 'returnValue' => array()),
        ));

        $biz = $this->getBiz();
        $product = $biz['order.product.'.ClassroomProduct::TYPE];

        $product->init(array('targetId' => 1));
        $product->price = 10;

        $params = array(
            'created_reason' => '班级用户导入订单',
            'price_type' => 'CNY',
        );
        $order = $this->getOrderFacadeService()->createSpecialOrder($product, 10, $params);

        $this->assertEquals('paid', $order['status']);
        $this->assertEquals(10 * 100, $order['price_amount']);
        $this->assertArraySubset($params, $order);
    }

    private function mockCurrency()
    {
        $biz = $this->getBiz();

        $currency = $this->getMockBuilder('Biz\OrderFacade\Currency')
                   ->disableOriginalConstructor()
                   ->getMock();
        $currency->isoCode = 'CNY';
        $biz['currency'] = $currency;
    }

    /**
     * @return OrderFacadeService
     */
    private function getOrderFacadeService()
    {
        return $this->createService('OrderFacade:OrderFacadeService');
    }
}
