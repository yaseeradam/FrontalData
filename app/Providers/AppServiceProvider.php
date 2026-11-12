<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
   {
    Blade::directive('basecdn', function ($expression) {
        return "<?php echo basecdn($expression); ?>";
    });
    Blade::directive('jannah_opt', function ($expression) {
        return "<?php echo jannah_opt($expression); ?>";
    });
    Blade::directive('numberformat', function ($expression) {
        return "<?php echo number_format($expression, 2); ?>";
    });

    Blade::directive('format', function ($expression) {
        list($text, $format) = explode(',', str_replace(['(', ')', '"', "'"], '', $expression));

        return "<?php
            \$text = trim($text);
            \$format = trim($format);
            if (empty(\$text)) {
                echo 'UNAVAILABLE';
            } else {
                \$chunks = [];
                \$currentPosition = 0;
                
                foreach (str_split(\$format) as \$length) {
                    \$length = (int) \$length;
                    if (\$currentPosition + \$length > strlen(\$text)) {
                        \$chunks[] = substr(\$text, \$currentPosition);
                        break;
                    }
                    \$chunks[] = substr(\$text, \$currentPosition, \$length);
                    \$currentPosition += \$length;
                }
                
                echo implode(' ', \$chunks);
            }
        ?>";
    });
    
    Blade::directive('uppercase', function ($expression) {
        return "<?php echo strtoupper($expression); ?>";
    });
    
    Blade::directive('ucwords', function ($expression) {
        return "<?php echo ucwords($expression); ?>";
    });
    
    Blade::directive('date', function ($expression) {
        return "<?php echo (new DateTime($expression))->format('M jS, H:i:s'); ?>";
    }); 
    
    Blade::directive('time', function ($expression) {
            return "<?php echo (new \DateTime($expression))->format('m-d H:i:s'); ?>";
        });

    }
        
    
}
