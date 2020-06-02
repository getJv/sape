<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    protected $guarded = [];

    function operationData()
    {

        switch ($this->operation) {
            case 'group':
                return $this->groupRoutine();
                break;

            case 'table':
                return $this->tableRoutine();
                break;

            case 'sum':
                return $this->sumRoutine();
                break;

            case 'avg':
                return $this->avgRoutine();
                break;

            default:
                return [];
                break;
        }
    }

    private function groupRoutine()
    {
        $fields = ProjectField::where('field_id', $this->field_id)->where('active', true)->get();
        $arr = $fields->groupBy('value')->map->count();

        $data = [];
        foreach ($arr as $key => $value) {
            array_push($data, [
                'item' => $key,
                'value' => $value,
            ]);
        }
        return $data;
    }

    private function tableRoutine()
    {
        $fields = ProjectField::where('field_id', $this->field_id)->where('active', true)->get();

        $map = $fields->map(function ($item) {
            return [
                'item' => $item->project->name,
                'value' => $item->value
            ];
        });

        return $map;
    }

    private function sumRoutine()
    {
        $arr = ProjectField::where('field_id', $this->field_id)->where('active', true)->pluck('value');

        $sum = 0;
        foreach ($arr as $key => $value) {
            if (!is_null($value))
                $sum += str_replace(',', '.', $value);
        }

        return [['item' => 'Somatório', 'value' => $sum]];
    }

    private function avgRoutine()
    {
        $arr = ProjectField::where('field_id', $this->field_id)->where('active', true)->pluck('value');

        //dd($arr);
        $sum = 0;
        foreach ($arr as $key => $value) {


            $sum += str_replace(',', '.', $value);
        }


        return [['item' => 'Média aritimética', 'value' => $sum / count($arr)]];
    }
}
