<?php

namespace Ddeboer\SoapClientBundle\Soap;

use BeSimple\SoapCommon\Converter\TypeConverterCollection;

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
    public function __construct($wsdl, array $classMap, TypeConverterCollection $converters, $debug = false)
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

        foreach($this->converters->all() as $typeConverter) {
            $typeMap[] = array(
                'type_name' => $typeConverter->getTypeName(),
                'type_ns'   => $typeConverter->getTypeNamespace(),
                'from_xml'  => function($input) use ($typeConverter) {
                    return $typeConverter->convertXmlToPhp($input);
                },
                'to_xml'    => function($input) use ($typeConverter) {
                    return $typeConverter->convertPhpToXml($input);
                },
            );
        }

        return $typeMap;
    }
}