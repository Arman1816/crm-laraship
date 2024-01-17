<?php


namespace Corals\Modules\Marketplace\Console\Commands;

use Corals\Modules\Marketplace\Models\OrderItem;
use Corals\Modules\Marketplace\Models\Product;
use Illuminate\Console\Command;

class CalculateTopSellers extends Command
{
    protected $signature = 'marketplace:calculate-top-sellers';
    protected $description = 'Marketplace: calculate top sellers products';

    public function handle()
    {
        $this->line('Start calculating...');
        $this->calculate();
        $this->line('Top sellers products calculated...');
    }

    public function calculate()
    {
        Product::query()->each(function (Product $product) {
            $skus = $product->sku()->pluck('code')->toArray();

            $totalSales = OrderItem::query()
                ->whereIn('sku_code', $skus)
                ->where('type', 'Product')
                ->count();

            if ($totalSales != $product->total_sales) {
                $product->update([
                    'total_sales' => $totalSales
                ]);
            }
        });
    }
}
