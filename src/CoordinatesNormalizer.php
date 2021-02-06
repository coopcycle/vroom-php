<?php

namespace Vroom;

use Geocoder\Model\Coordinates;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CoordinatesNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->toArray();
    }

    public function supportsNormalization($data, $format = null)
    {
        return \is_object($data) && $data instanceof Coordinates;
    }
}
