<?php

namespace Ddeboer\SoapClientBundle\BindingAdapter;

use Ddeboer\SoapClientBundle\TypeConverter\DateTimeConverter;

class DocumentLiteralWrappedAdapter
{
    protected $soapClient;
    protected $wsdl;
    
    public function __construct(\SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }
    
    public function call($method, array $parameters)
    {
        $request = new \stdClass();
        foreach ($parameters as $key => $value) {
            $request->$key = $value;
        }
        
        $result = $this->soapClient->__soapCall($method, array($request));
        return $result->{$method . 'Result'};
    }       
}