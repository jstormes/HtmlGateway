<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 1/3/2018
 * Time: 11:47 AM
 */

use PHPUnit\Framework\TestCase;

class HtmlGatewayTest extends TestCase
{
    public function testConstructor()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway();
        $this->assertTrue(is_object($htmlGateway));
        $this->assertAttributeEmpty("template",$htmlGateway);
        $this->assertAttributeEmpty("helpers",$htmlGateway);


        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway("somefile.phtml");
        $this->assertTrue(is_object($htmlGateway));
        $this->assertAttributeNotEmpty("template",$htmlGateway);
        $this->assertAttributeEmpty("helpers",$htmlGateway);

        $helpers['simple1'] = $this->getMockForAbstractClass('\JStormes\HtmlGateway\AbstractViewHelper');
        $helpers['simple2'] = $this->getMockForAbstractClass('\JStormes\HtmlGateway\AbstractViewHelper');
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway("somefile.phtml",$helpers);
        $this->assertTrue(is_object($htmlGateway));
        $this->assertAttributeNotEmpty("template",$htmlGateway);
        $this->assertAttributeNotEmpty("helpers",$htmlGateway);
    }

    public function testMagicMethodHelper()
    {

        $helperMock['test'] = $this->getMockForAbstractClass('\JStormes\HtmlGateway\AbstractViewHelper');
        $helperMock['test']->method('execute')
            ->willReturn('simple test');

        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('somefile.phtml',$helperMock);
        $text = $htmlGateway->test();
        $this->assertSame('simple test',$text);

        $this->expectException(\Exception::class);
        $htmlGateway->badMethodCall();

    }

    public function testAddHelper()
    {
        $helperMock = $this->getMockForAbstractClass('\JStormes\HtmlGateway\AbstractViewHelper');
        $helperMock->method('execute')
            ->willReturn('simple test');

        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway();
        $htmlGateway->addHelper('test',$helperMock);
        $text=$htmlGateway->test();

        $this->assertSame('simple test',$text);

    }

    public function testSetData()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway();
        $htmlGateway->setData('this is a test');

        $this->assertAttributeContains('this is a test','data',$htmlGateway);

        $this->assertSame('this is a test',$htmlGateway->data());

    }


}