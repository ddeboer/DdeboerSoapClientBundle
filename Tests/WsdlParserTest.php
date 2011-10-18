<?php

namespace Ddeboer\SoapClientBundle\Tests\Wsdl;

use Ddeboer\SoapClientBundle\WsdlParser;

class WsdlParserText extends \PHPUnit_Framework_TestCase
{
    public function testParseAskaWsdl()
    {
        $wsdl = __DIR__ . '/Fixtures/askawebservice.xml';
        $parser = new WsdlParser($wsdl);        
        $document = $parser->parse();
        $this->assertEquals(27, count($document->getOperations()));
        
        $type = $document->getOperation('GetOrganisationsById')->getInputMessage()->getParts()->first()->getType();
        
//        $element = ($document->getElementByName($type)->getSubElements()->first());
//        var_dump($element->getType());
//        var_dump($element->getPhpType());
////        var_dump($document->getTypes());
    }
}