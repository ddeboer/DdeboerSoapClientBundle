<?php

namespace SoapClientBundle\Tests;

use Ddeboer\SoapClientBundle\WsdlParser;
use Ddeboer\SoapClientBundle\CodeGenerator;

class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateClientClass()
    {
        $document = $this->getDocument();        
        $codeGenerator = new CodeGenerator($document, __DIR__ . '/../Resources/views');        
        
        $directory = __DIR__ . '/../../../../../src/MTTM/AskaClientBundle';

        $codeGenerator->generateClientClass($directory, 'AskaClient', 'MTTM\AskaClientBundle');
    }
    
    public function testGenerateProxyClasses()
    {        
        $codeGenerator = new CodeGenerator($this->getDocument(), __DIR__ . '/../Resources/views');
        $directory = __DIR__ . '/../../../../../src/MTTM/AskaClientBundle/Proxy/';
        $codeGenerator->generateProxyClasses($directory, 'MTTM\AskaClientBundle\Proxy');
        
    }
    
    protected function getDocument()
    {
        $wsdl = __DIR__ . '/Fixtures/askawebservice.xml';
        $parser = new WsdlParser($wsdl);        
        return $parser->parse();
        
        
        $msg = $document->getOperation('GetAddressals')->getInputMessage();
//        if ($msg->getParts()->count() == 1) {
//            $type = $msg->getParts()->first()->getType();
//            var_dump($document->getTypeByName($type));
//            
//            die('ja');
//        }

    }
}