<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLinkRequest;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LinkController extends Controller
{
    public function create(): View
    {
        $token = Link::generateToken();
        return view('link.create', ['token'=> $token]);
    }

    public function store(CreateLinkRequest $request): RedirectResponse
    {
        $expiresAt = $request->input('expires_in') ?  Carbon::now()->addMinutes($request->input('expires_in'))->toDateTime() : null;

        Link::create([
            'token' => $request->input('token'),
            'max_clicks' => $request->input('max_clicks', 0),
            'expires_at' => $expiresAt,
        ]);

        return redirect()->route('link.create');
    }

    public function show(string $token, Request $request): RedirectResponse
    {
        $link = Link::where('token', $token)->firstOrFail();

        if ($link->isExpired() || $link->hasMaxClicks()) {
            throw new NotFoundHttpException();
        }

        $link->incrementClicks();

        return redirect()->route('link.create');
    }

}
