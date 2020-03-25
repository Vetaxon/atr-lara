<?php


namespace App\Services\Responses;

use App\Services\Responses\Contracts\StreamResponsesFactoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamResponsesFactory implements StreamResponsesFactoryInterface
{
    /**
     * @param Builder $query
     * @param array $tableMap
     * @param array $options
     * @return StreamedResponse
     */
    public function getCsvQueryStream(Builder $query, array $tableMap, array $options = []) : StreamedResponse
    {
        return new StreamedResponse(function() use ($tableMap, $query, $options) {

            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_keys($tableMap));

            $query->chunk($options['chunk'] ?? 50, function (Collection $data) use ($handle, $tableMap) {
                foreach ($data->toArray() as $dataPrepared) {
                    fputcsv($handle, array_map(function (callable $call) use ($dataPrepared) {
                        return $call($dataPrepared);
                    }, $tableMap));
                }
            });

            fclose($handle);
        }, 200, array_merge([
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment',
            'Content-Filename' => $options['filename'] ?? 'export.csv',
        ], $options['headers'] ?? []));
    }

}
