<?php


namespace AppBundle\Utils;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonUtils
{
    public static function deserializeFromJson($jsonData, $class)
    {

        $postfix = '';

        $serializer = new Serializer(
            array(new GetSetMethodNormalizer(), new ArrayDenormalizer()),
            array(new JsonEncoder())
        );

        if( is_array(json_decode($jsonData)) ) {
            $postfix = '[]';
        }

        return $serializer->deserialize($jsonData, $class.$postfix, 'json');
    }

    public static function isJSONRequest($request)
    {
        /** @var Request $request */
        return 0 === strpos($request->headers->get('Content-Type'), 'application/json');
    }
}