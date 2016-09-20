<?php
namespace App\Repositories;

use Elasticsearch\Common\Exceptions\Curl\OperationTimeoutException;
use Kurio\Common\Repositories\ElasticRepositories;

class ArticleElasticRepository extends ElasticRepositories
{
    public function search(array $filter_type, $limit = 20, $offset = null)
    {
        $offset = (empty($offset)) ? 0 : $offset;
        $query_result = array();

        $params = [];
        $params['index'] = $this->getIndexName();
        $params['type'] = 'article';

        if (!empty($filter_type['url'])) {
            $query_result[] = $this->selectQuery($filter_type['url'], 'url');
        } elseif (!empty($filter_type['id'])) {
            $query_result[] = $this->selectQuery($filter_type['id'], 'id');
        } else {
            //keyword
            if (!empty($filter_type['keyword'])) {
                $fields_key = ['title', 'content'];
                $query_result[] = $this->selectQuery($filter_type['keyword'], $fields_key);
            }
            // topic
            if (!empty($filter_type['topic'])) {
                $query_result[] = $this->selectQuery($filter_type['topic'], 'topic_ids');
            }
            // pin
            if (!empty($filter_type['pinned'])) {
                $query_result[] = $this->selectQuery($query_pin = $filter_type['pinned'], 'pinned');
            }
        }

        $params['body'] = ['query' => [
            'bool' => [
                'must' => $query_result,
            ]
        ],
            'sort' => [
                'published_at' => ['order' => 'desc'],
                '_score' => ['order' => 'desc']
            ],
            'from' => $offset,
            'size' => $limit];

        try {
            $result = $this->elasticsearch->search($params);
        } catch (OperationTimeoutException $timeoutEx) {
            throw $timeoutEx;
        } catch (\Exception $ex) {
            return response()->json(['error' => 'Error while search articles'], 500);
        }
        return ($this->fetchElasticResult($result) == false) ? [] : $this->fetchElasticResult($result);
    }

    public function destroy($id)
    {
        $params = [
            'index' => $this->getIndexName(),
            'type' => 'article',
            'id' => $this->findById($id)
        ];

        return $result = $this->elasticsearch->delete($params);

    }

    public function pin($id, array $data)
    {

        $params = [
            'index' => $this->getIndexName(),
            'type' => 'article',
            'id' => $this->findById($id),
            'body' => [
                'doc' => [
                    'pinned_until' => strtotime($data['pinned_until']),
                    'pinned' => $data['pinned'],
                    'curated_by' => $data['curated_by'],
                    'curated' => 1,
                    'curated_at' => strtotime(date('Y-m-d H:i:s'))
                ]
            ]
        ];
        return $this->elasticsearch->update($params);

    }
    
    public function findById($id)
    {
        $params['index'] = $this->getIndexName();
        $params['type'] = 'article';
        $params['body'] = [
            'query' => [
                'multi_match' => [
                    'query' => $id,
                    'fields' => 'id'
                ]
            ],
        ];

        $result = $this->elasticsearch->search($params);
        return ($this->fetchId($result) == false) ? [] : $this->fetchId($result);

    }

    protected function fetchId(array $object)
    {
        if (!isset($object['hits']) or $object['hits']['total'] <= 0) {
            return false;

        }
        $id_elastic = $object['hits']['hits'][0]['_id'];

        return $id_elastic;

    }

    public function selectQuery($query, $fields)
    {
        $query_result[] = ['multi_match' => ['query' => $query,
            'type' => 'most_fields',
            'operator' => 'and',
            'fields' => $fields
        ]];
        return $query_result;

    }

}
    