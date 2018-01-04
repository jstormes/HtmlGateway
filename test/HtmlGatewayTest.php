<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 1/3/2018
 * Time: 11:47 AM
 */

require_once ('Entity/dummyEntity.php');

use PHPUnit\Framework\TestCase;


class HtmlGatewayTest extends TestCase
{
    public function setUp()
    {
        // All test are relative to our script directory.
        chdir(__DIR__);
    }

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
        $obj=$htmlGateway->setData('this is a test');
        $this->assertEquals($obj,$htmlGateway,'setData() is no longer fluent');
        $this->assertAttributeContains('this is a test','data',$htmlGateway);
        $this->assertSame('this is a test',$htmlGateway->data());

    }

    public function testRender()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $htmlGateway->render('some data');
        $this->assertSame('some data', $htmlGateway->data());

        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $htmlGateway->setData('this is a test');
        $htmlGateway->render();
        $this->assertSame('this is a test',$htmlGateway->data());

        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $fileText = file_get_contents('templates/simple.phtml');
        $testText = $htmlGateway->render();
        $this->assertSame($fileText,$testText);

        $this->expectException(\Exception::class);
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('Not found');
        $htmlGateway->render();

        $this->expectException(\Exception::class);
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/bad-template.phtml');
        $htmlGateway->render();

    }

    public function testAddToSection()
    {
        $htmlGatewayTest =  new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $htmlGatewayTest->setData('this is a test');
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/test-section.phtml');
        $htmlGateway->addToSection('test',$htmlGatewayTest);
        $gateways = $this->getObjectAttribute($htmlGateway,'gateways');
        $this->assertContains($htmlGatewayTest,$gateways['test']);
        $this->assertSame('this is a test', $htmlGatewayTest->data());

        $htmlGatewayTest =  new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/test-section.phtml');
        $htmlGateway->addToSection('test',$htmlGatewayTest,'this is a test');
        $gateways = $this->getObjectAttribute($htmlGateway,'gateways');
        $this->assertContains($htmlGatewayTest,$gateways['test']);
        $this->assertSame('this is a test', $htmlGatewayTest->data());

    }

    public function testSection()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $htmlSection = new \JStormes\HtmlGateway\HtmlGateway('templates/test-section.phtml');
        $htmlGateway->addToSection('test',$htmlSection);
        $text = $htmlGateway->section('test');
        $fileText = file_get_contents('templates/test-section.phtml');
        $this->assertSame($text,$fileText);

    }

    public function testEscape()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $text = $htmlGateway->escape('this is a test &');
        $this->assertSame($text,'this is a test &amp;');

    }

    public function testE()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway('templates/simple.phtml');
        $text = $htmlGateway->e('this is a test &');
        $this->assertSame($text,'this is a test &amp;');
    }

    public function testPrototype()
    {
        $htmlGateway = new \JStormes\HtmlGateway\HtmlGateway();
        $obj=$htmlGateway->setPrototype('this is a test');
        $this->assertEquals($obj,$htmlGateway,'setPrototype() is no longer fluent');
        $this->assertAttributeContains('this is a test','data',$htmlGateway);
        $this->assertSame('this is a test',$htmlGateway->data());

    }

    public function testFetch()
    {
        $data = new dummyEntity();

        $request = $this->getMockBuilder('Psr\Http\Message\ServerRequestInterface')->getMock();
        $request->method('getParsedBody')->willReturn(['action'=>'save','field1'=>'value1']);

        $htmlGateway = new JStormes\HtmlGateway\HtmlGateway();
        $htmlGateway->setPrototype($data);

        $data = $htmlGateway->fetch($request);

        $this->assertSame($data->getField1(),'value1');
    }

    public function testProcess_Save()
    {
        $data = new dummyEntity();
        $request = $this->getMockBuilder('Psr\Http\Message\ServerRequestInterface')->getMock();
        $request->method('getParsedBody')->willReturn(['action'=>'save','field1'=>'value1']);
        $request->method('getMethod')->willReturn('POST');
        $htmlGateway = new JStormes\HtmlGateway\HtmlGateway();
        $htmlGateway->setPrototype($data);
        $data = $htmlGateway->process($request);
        $this->assertSame($data->getField1(),'value1');
        $this->assertTrue($data->saved);
        $this->assertFalse($data->deleted);

    }

    public function testProcess_Delete()
    {
        $data = new dummyEntity();
        $request = $this->getMockBuilder('Psr\Http\Message\ServerRequestInterface')->getMock();
        $request->method('getParsedBody')->willReturn(['action'=>'delete','field1'=>'value1']);
        $request->method('getMethod')->willReturn('POST');
        $htmlGateway = new JStormes\HtmlGateway\HtmlGateway();
        $htmlGateway->setPrototype($data);
        $data = $htmlGateway->process($request);
        $this->assertSame($data->getField1(),'value1');
        $this->assertFalse($data->saved);
        $this->assertTrue($data->deleted);

    }


}