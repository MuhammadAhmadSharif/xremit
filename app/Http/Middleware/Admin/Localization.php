<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Constants\GlobalConst;
use App\Models\Admin\Language;
use App\Constants\LanguageConst;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $default_language = Language::where('status',GlobalConst::ACTIVE)->first();
            $default_language_code = $default_language->code ?? LanguageConst::NOT_REMOVABLE;
            if(session()->has('local')) {
                $local = session()->get("local");
                $local_dir = session()->get('local_dir');
            }else {
                $local = $default_language_code;
                $local_dir = $default_language?->dir ?? "ltr";
            }
            App::setLocale($local);
            session()->put('local_dir',$local_dir);
        }catch(Exception $e) {
            //handle error
        }

        return $next($request);
    }
}
