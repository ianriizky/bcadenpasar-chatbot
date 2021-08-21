<?php

namespace App\Providers;

use App\Enum\OrderStatus;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * {@inheritDoc}
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * List of request parameter that can be resolved into an enum instance.
     *
     * @var string[]
     */
    protected $enums = [
        'enumOrderStatus' => OrderStatus::class,
    ];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::match(['get', 'post'], '/botman', function () {
                $this->mapBotManCommands();
            })->middleware('web_without_csrf');
        });

        $this->resolveEnumBinding();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Defines the BotMan "hears" commands.
     *
     * Note: Please don't remove this below file, as it will be used also on the artisan command `botman:tinker`
     *
     * @return void
     */
    protected function mapBotManCommands()
    {
        require base_path('routes/botman.php');
    }

    /**
     * Resolve enum binding from route parameter.
     *
     * @return void
     */
    protected function resolveEnumBinding()
    {
        foreach ($this->enums as $name => $class) {
            Route::bind($name, function ($value) use ($class) {
                return $class::from($value);
            });
        }
    }
}
