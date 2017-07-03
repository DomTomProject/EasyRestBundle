<?php

namespace DomTomProject\EasyRestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Base;
use Symfony\Component\HttpFoundation\JsonResponse;
use DomTomProject\EasyRestBundle\Exception\InternalErrorJsonException;
use DomTomProject\EasyRestBundle\Exceptionn\NotFoundHttpJsonException;

/**
 *  Custom controller base
 *  @author Damian Zschille <crunkowiec@gmail.com>
 */
class Controller extends Base {

    /**
     * Returns JsonResponse with message
     * 
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function renderJsonMessage(string $message, int $statusCode = 200): JsonResponse {
        $data = [
            'msg' => $message,
            'code' => $statusCode,
        ];

        return new JsonResponse($data, $statusCode);
    }

    /**
     * Returns JsonResponse
     * 
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function renderJson(array $data, int $statusCode = 200): JsonResponse {
        $data = [
            'data' => $data,
            'code' => $statusCode,
        ];

        return new JsonResponse($data, $statusCode);
    }

    /**
     * Returns data serialized by JMSSerializerBundle
     * 
     * @param mixed $entities Entity object or array of object, arrays
     * @param int $statusCode
     * @return JsonResponse
     */
    public function renderSerializedJson($entities, int $statusCode = 200, JsonResponse $response = null): JsonResponse {
        $serializer = $this->get($this->getParameter('domtom_easy_rest.serializer_service'));

        if (empty($entities)) {
            throw new NotFoundHttpJsonException();
        }

        if (is_array($entities)) {
            $entityClass = get_class($entities[0]);
            $data = [
                'data' => $entities,
                'count' => count($entities),
            ];
        } else if (is_object($entities)) {
            $entityClass = get_class($entities);
            $data = [
                'data' => $entities,
                'count' => 1,
            ];
        } else {
            throw new InternalErrorJsonException('Entites variable must be array or object.');
        }

        // total count
        $totalCount = $this
                ->getDoctrine()
                ->getRepository($entityClass)
                ->createQueryBuilder('e')
                ->select('count(e.id)')
                ->getQuery()
                ->getSingleScalarResult();

        $data['total'] = intval($totalCount);
        $data['code'] = $statusCode;
        $json = $serializer->serialize($data, 'json');

        if (empty($response)) {
            return new JsonResponse($json, $statusCode, [], true);
        }

        $response->setContent($json)->setStatusCode($statusCode);
        return $response;
    }

}
