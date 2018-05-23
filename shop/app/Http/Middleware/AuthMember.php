<?php

namespace App\Http\Middleware;

use Closure;

class AuthMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //更具config - auth 文件中指定的guard 来选择 登陆前后端人员的分类
       $bool = auth()->guard('member')->check();//检查是否登录
        if(!$bool){//未登录时的情况
            return redirect('/admin/test');
        }
        return $next($request);
    }
}
