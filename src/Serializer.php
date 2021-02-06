<?php

namespace Vroom;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as BaseSerializer;

class Serializer extends BaseSerializer
{
    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);

        $normalizers = [
            new CoordinatesNormalizer(),
            new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter),
        ];
        $encoders = [
            new JsonEncoder()
        ];

        parent::__construct($normalizers, $encoders);
    }
}
