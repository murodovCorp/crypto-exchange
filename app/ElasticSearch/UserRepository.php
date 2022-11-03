<?php

namespace App\ElasticSearch;

use App\Models\User;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class UserRepository {


    public function __construct(private Client $elasticsearch){}

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function search(Request $request): Collection
    {
        $items = $this->searchOnElasticsearch($request->input('query'));
        return $this->buildCollection($items);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    private function searchOnElasticsearch(string $query = ''): array
    {
        $model = new User;
        return $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['name^5', 'email'],
                        'query' => $query,
                    ],
                ],
            ],
        ])->asArray();
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return User::query()->findMany($ids)
            ->sortBy(function ($article) use ($ids) {
                return array_search($article->getKey(), $ids);
            });
    }

}
