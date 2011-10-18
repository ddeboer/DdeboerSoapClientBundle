<?php

namespace Ddeboer\SoapClientBundle\Soap;

use BeSimple\SoapBundle\Converter\ConverterRepository;

class SoapClientFactory
{
    private $wsdl;
    private $classMap;
    private $converters;
    private $debug;
    private $username;
    private $password;
    
    /**
     * Construct a PHP SOAP client
     * 
     * @param string $wsdl      URI to the WSDL
     * @param array $classMap
     * @param ConverterRepository $converters
     * @param type $debug 
     */
    public function __construct($wsdl, array $classMap, ConverterRepository $converters, $debug = false)
    {
        $this->wsdl = $wsdl;
        $this->classMap = $classMap;
        $this->converters = $converters;
        $this->debug = $debug;
    }

    /**
     * Set authentication for accessing the WSDL itself, if that is protected
     * with a password
     *
     * @param string $username
     * @param string $password
     */
    public function setAuth($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Create a PHP SOAP client and configure it
     *
     * @return \SoapClient
     */
    public function create()
    {
        $options = array(                
            'classmap'   => $this->classMap,
            'typemap'    => $this->createTypeMap(),
            'features'   => SOAP_SINGLE_ELEMENT_ARRAYS,
            'cache_wsdl' => $this->debug ? WSDL_CACHE_NONE : WSDL_CACHE_DISK,
            'trace'      => true
        );

        if ($this->username && $this->password) {
            $options['login'] = $this->username;
            $options['password'] = $this->password;
        }
        
        return new \SoapClient($this->wsdl, $options);
    }
    
    /**
     * Create SOAP type map
     * 
     * @return array 
     */
    private function createTypeMap()
    {
        $typeMap = array();
        
        $request = new \BeSimple\SoapBundle\Soap\SoapRequest();
        $response = null;

        foreach($this->converters->getTypeConverters() as $typeConverter) {
            $typeMap[] = array(
                'type_name' => $typeConverter->getTypeName(),
                'type_ns'   => $typeConverter->getTypeNamespace(),
                'from_xml'  => function($input) use ($request, $typeConverter) {
                    return $typeConverter->convertXmlToPhp($request, $input);
                },
                'to_xml'    => function($input) use ($response, $typeConverter) {
                    return $typeConverter->convertPhpToXml($response, $input);
                },
            );
        }
        
        return $typeMap;
    }
}