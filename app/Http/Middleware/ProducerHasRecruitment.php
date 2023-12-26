<?php

namespace App\Http\Middleware;

use App\Models\Recruitment;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProducerHasRecruitment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $recruitment_id = $request->route()->parameters('recruitment_id');
        if ( Recruitment::where('id', $recruitment_id)->where('producer_id', Auth::user()->id)->count() )
        {
            return $next($request);
        }

        return redirect()->route('welcome');
    }
}
