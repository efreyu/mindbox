<?php

namespace TrafficIsobar\Mindbox\app\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use League\Flysystem\Util;

/**
 * @OA\Server(
 *     url="http://tender.jti.jti.t-agency.ru/"
 * )
 *
 * @OA\Info(
 *     title="Agency3 docs",
 *     version="0.0.1"
 * )
 *
 */
class MindboxController extends Controller
{
    public function assets(Request $request)
    {
        try {
            $path = dirname(__DIR__, 4).'/publishable/'.Util::normalizeRelativePath(urldecode($request->path));
        } catch (\LogicException $e) {
            abort(404);
        }

        if (File::exists($path)) {
            $mime = '';
            if (Str::endsWith($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (Str::endsWith($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        return response('', 404);
    }
}
