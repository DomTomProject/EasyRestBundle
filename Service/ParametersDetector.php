<?php

namespace DomTomProject\EasyRestBundle\Service;

class ParametersDetector
{
    public function detectListingParameters(array &$data) {

        $order = null;
        $orderDir = 'asc';
        $limit = 10;
        $offset = 0;

        if (isset($data['order_by'])) {
            $orderBy = $this->convertFromSnakeToCamel($data['order_by']);
            unset($data['order_by']);

            if (isset($data['order_dir'])) {
                $orderDir = $data['order_dir'];
                unset($data['order_dir']);
            }

            $order = [
                $orderBy => $orderDir
            ];
        }

        if (isset($data['page'])) {
            $page = $data['page'];
            if (isset($data['limit'])) {
                $limit = $data['limit'];
                unset($data['limit']);
            }

            $offset = ($page - 1) * $limit;

            if (isset($data['offset'])) {
                unset($data['offset']);
            }
            unset($data['page']);
        } else {
            if (isset($data['limit'])) {
                $limit = $data['limit'];
                unset($data['limit']);
            }

            if (isset($data['offset'])) {
                $offset = $data['offset'];
                unset($data['offset']);
            }
        }

        $parameters = [
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset,
        ];

        return $parameters;
    }

    private function convertFromSnakeToCamel(string $field): string {
        $fieldPartials = explode('_', $field);

        $newField = '';
        foreach ($fieldPartials as $paramPartial) {
            $newField .= ucfirst(strtolower($paramPartial));
        }

        return lcfirst($newField);
    }

    public function convertDataFromSnakeToCamel(array $data) : array {
        $camelData = [];

        foreach ($data as $parameters => $value) {
            $camelData[$this->convertFromSnakeToCamel($parameters)] = $value;
        }

        return $camelData;
    }


}