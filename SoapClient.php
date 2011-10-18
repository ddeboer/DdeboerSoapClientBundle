<?php

namespace Ddeboer\SoapClientBundle;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A base SOAP client
 * 
 * It is not abstract, for it can be used directly as a server, if you wish so.
 * I recommend, however, to generate a SOAP client bundle first, and have that
 * client extend this base SoapClient.
 */
class SoapClient
{
    protected $wsdl;
    
    /**
     * PHP SOAP client used internally
     * 
     * @var \SoapClient
     */
    protected $soapClient;
    
    protected $eventDispatcher;
    
    protected $bindingAdapter;
    
    public function __construct($adapter, $wsdl)
    {
        $this->adapter = $adapter;
        $this->wsdl = $wsdl;
    }

    /**
     * Set event dispatcher on client, so client can produce events
     * 
     * @param EventDispatcher $eventDispatcher 
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function call($method, array $parameters = array())
    {
        $this->beforeCall();
        
        $response = $this->adapter->call($method, $parameters);
        
        return $this->processCallResponse($method, $parameters, $response);        
    }
    
    /**
     * Called before every call to the SOAP API
     * 
     * This method can be overridden to do something before a call is made
     * to the SOAP server.
     */
    protected function beforeCall()
    {
    }
    
    /**
     * Process the response received from the SOAP server
     * 
     * This method can be overriden to process the response received from the
     * SOAP server. By default, it just returns that response.
     * 
     * @param string $method
     * @param string $parameters
     * @param mixed $response
     * @return mixed
     */
    protected function processCallResponse($method, $parameters, $response)
    {
        return $response;
    }
    
    /**
     * Get PHP SOAP client
     *
     * @return \SoapClient
     */
//    protected function getSoapClient()
//    {
//        if (null === $this->soapClient) {
//            $this->soapClient = new \SoapClient($this->wsdl, array(
//                // Because PHP misses built-in support for XML dateTime
////                'typemap'   => $this->getTypeMap()
//            ));
//        }
//        return $this->soapClient;
//    }    
    
//    protected function getTypeMap()
//    {
//        return array(
//            array(
//                'type_ns'   => '{{ xsd_namespace }}',
//                'type_name' => 'dateTime',
//                'to_xml'    => 'toDateTimeXml',
//                'from_xml'  => 'fromDateTimeXml'
//            )
//        );
//    }
//    
//    protected function toDateTimeXml($xml)
//    {
//        return new \DateTime(strip_tags($xml));
//    }
//    
//    protected function fromDateTimeXml(\DateTime $dateTime)
//    {
//        return '<dateTime>' . $dateTime->format('Y-m-d\TH:i:sP') . '</dateTime>';
//    }
}