<?php

namespace App\Http\Controllers;

use App\Enum\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\StockPriceService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\Http\Resources\StockPriceResource;
use App\Http\Requests\GetLatestStockPriceRequest;
use App\Http\Requests\GetStockPriceReportRequest;

class StockPriceController
{
    public function __construct(private StockPriceService $service)
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/api/latest-stock-price",
     *     tags={"Stock Price"},
     *     operationId="getStockPrice",
     *     summary="Get latest stock price data for the specified symbol",
     *     description="Get latest stock price data for the specified symbol",
     *     @OA\Parameter(
     *         description="Stock symbol",
     *         in="query",
     *         name="symbol",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/StockSymbol"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Latest stock price data returned",
     *         @OA\JsonContent(ref="#/components/schemas/StockPriceResource"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid parameters provided",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The selected symbol is invalid.",
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="symbol",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The selected symbol is invalid.",
     *                     ),
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     *
     * @param GetLatestStockPriceRequest $request
     * @return StockPriceResource|null
     */
    public function index(GetLatestStockPriceRequest $request): ?StockPriceResource
    {
        $stockPrice = $this->service->getLatestPrice($request->input('symbol'));

        return $stockPrice ? StockPriceResource::make($stockPrice) : null;
    }

    /**
     * @OA\Get(
     *     path="/api/report",
     *     tags={"Stock Price"},
     *     operationId="getStockPriceReport",
     *     summary="Get stock price report",
     *     description="Get stock prices and percentage change based on provided dates",
     *     @OA\Parameter(
     *         description="Stock symbol",
     *         in="query",
     *         name="symbol",
     *         required=true,
     *         @OA\Schema(ref="#/components/schemas/StockSymbol"),
     *     ),
     *     @OA\Parameter(
     *         description="Start date",
     *         in="query",
     *         name="start",
     *         required=true,
     *         example="2024-08-13 19:50:00",
     *     ),
     *     @OA\Parameter(
     *         description="End date",
     *         in="query",
     *         name="end",
     *         required=true,
     *         example="2024-08-13 19:59:00",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stock price report returned",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="stockPrices",
     *                 description="The stock prices between the start date and the end date",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/StockPriceResource"),
     *             ),
     *             @OA\Property(
     *                 property="percentChange",
     *                 description="The percentage change",
     *                 type="float",
     *                 example=0.02,
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid parameters provided",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The selected symbol is invalid.",
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="symbol",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The selected symbol is invalid.",
     *                     ),
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     *
     * @param GetStockPriceReportRequest $request
     * @return array
     */
    public function getReport(GetStockPriceReportRequest $request): array
    {
        $startDate = Carbon::parse($request->input('start'));
        $endDate = Carbon::parse($request->input('end'));
        $symbol = $request->input('symbol');
        $reportData = $this->service->getReport($symbol, $startDate, $endDate);

        return [
            'stockPrices' => StockPriceResource::collection($reportData['stockPrices']),
            'percentChange' => $reportData['percentChange'],
        ];
    }


    /**
     * @param Request $request
     * @return View|Factory|Application
     */
    public function show(Request $request): View|Factory|Application
    {
        $symbol = $request->input('symbol');
        $stock = Stock::symbols()[$symbol] ?? null;

        if (is_null($stock)) {
            abort('404');
        }

        $latestPrice = $this->service->getLatestPrice($symbol);
        $percentChange = $this->service->getPercentChange($symbol);

        return view('stocks.show', [
            'stock' => $stock,
            'latestPrice' => $latestPrice,
            'percentChange' => $percentChange,
        ]);
    }
}
