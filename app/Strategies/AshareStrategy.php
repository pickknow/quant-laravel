<?php

namespace App\Strategies;

use App\Models\Industry;
use App\Models\Stock;
use App\Interfaces\AshareInterface;
use App\Services\AkshareService;
use App\Helps\Functools;
use App\Models\Stockhistory;
use DateTime;
use DateInterval;

class AshareStrategy implements AshareInterface
{
    public function __construct(protected AkshareService $aShare)
    {
    }

    public function __call(string $name, array|null $data = null)
    {
        [$func, $after, $before] = ["_" . $name, $name . "_after", $name . "_before",];
        $data = method_exists($this, $before) ? $this->$before($data) : $data;
        $data = method_exists($this, $func) ? $this->$func($name, $data) : $data;
        $data = method_exists($this, $after) ? $this->$after($data) : $data;
        return $data;
    }
    public function test()
    {
        print "this is a default test in AshareStrategy.";
    }

    /**
     * first: get the induestries of the market.
     * second: get stock codes of each of them
     * third: get infomation of each stock
     */
    public function initData()
    {
        dump('----industries----');
        $this->industries();
        dump('----stocksOfIndustry----');
        $this->stocksOfIndustry();
        dump('----stockInfo----');
        $this->stockInfo();
    }

    // get all industries, this only run once.
    public function industries()
    {
        Industry::preventDouble();
        $result =  $this->aShare->industries();
        return Industry::zipCreate($result);
    }

    // get all sotcks of industery, and save them to datebase.
    public function stocksOfIndustry()
    {
        app()->make("CES")->info('stocksOfIndustry');
        Industry::all()->map(function ($i) {
            Functools::of($this->aShare->stocksOfIndustry(symbol: $i->name))
                ->reduce(fn ($p, $n) => [[...$p[0], $n[1]], [...$p[1], $n[1] . $n[2]],], [[], []])
                ->map(
                    fn ($r) => Industry::where("id", $i->id)->update([
                        "nums" => implode(",", $r[0]),
                        "nums_names" => implode(",", $r[1])
                    ])
                );
        });
    }

    public function diaryHistorySave(
        $code,
        $start = null,
        $end = null,
        $save = false
    ) {
        $start = $start ?: date("Ymd");
        $end = $end ?: $start;
        $result = $this->aShare->diaryHistory(
            symbol: $code,
            start_date: $start,
            end_date: $end,
            period: "daily",
            adjust: "qfq"
        );
        $save && $save($result);
        return $result;
    }

    public function getAllStocksCodes(): array
    {
        //['000001','000002']
        return Industry::all("nums")
            ->map(fn ($x) => explode(",", $x->nums))
            ->reduce(fn ($p, $n) => $p + $n, []);
    }

    public function oneDayAllStocks($start = null, $save = false)
    {
        collect($this->getAllStocksCodes())
            ->map(
                fn ($x) => $this->diaryHistorySave(
                    $x,
                    start: $start,
                    save: $save
                )
            );
    }

    // this function is going to fetch all stock date today.
    public function diaryHistory()
    {
        $this->oneDayAllStocks(save: true);
    }

    public function stockInfo()
    {
        collect($this->getAllStocksCodes())
            ->eachThrough([
                fn ($x) => $this->aShare->stockInfo(symbol: $x),
                fn ($x) => Stock::zipOneCreate($x),
            ]);
    }

    public function calStocksOfInsdutry()
    {
        Industry::all()
            ->map(function ($item) {
                $item->stocks =  count(explode(',', $item->nums));
                $item->save();
            });
    }

    public function getHistoryDate(): array
    {
        $start = date("Ymd");
        $last = Stockhistory::orderByDesc('id')->first();
        if ($last) {
            $end = $last->date;
        } else {
            $dateTime = new DateTime($start);
            $dateTime->sub(new DateInterval('P30D'));
            $end = $dateTime->format('Ymd');
        }
        return [$start, $end];
    }

    public function stocksHistory()
    {
        [$start, $end] = $this->getHistoryDate();
        dd($start, $end);
        collect($this->getAllStocksCodes())
            ->eachThrough([
                fn ($x) => $this->aShare->stockInfo(symbol: $x),
                fn ($x) => Stockhistory::zipCreate($x),
            ]);
    }
}
