<?php


namespace Corals\Modules\Marketplace\Jobs;


use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Services\ProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};


class CopyProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Product
     */
    protected Product $product;

    /**
     * @var array
     */
    protected array $parameters;

    /**
     * CopyProductJob constructor.
     * @param Product $product
     * @param array $parameters
     */
    public function __construct(Product $product, array $parameters)
    {
        $this->product = $product;
        $this->parameters = $parameters;
    }


    /**
     * @param ProductService $productService
     */
    public function handle(ProductService $productService)
    {
        $productService->copyProduct($this->product, $this->parameters);
    }


}
