<?php

namespace App\Http\Controllers;

use App\Models\PortfolioPhoto;
use Illuminate\Http\Response;

class PortfolioMediaController extends Controller
{
    public function __invoke(PortfolioPhoto $portfolioPhoto, string $field = 'image'): Response
    {
        abort_unless(in_array($field, ['image', 'poster'], true), 404);

        $mediaPath = $field === 'poster'
            ? $portfolioPhoto->poster_path
            : $portfolioPhoto->image_path;

        abort_unless(is_string($mediaPath) && str_starts_with($mediaPath, 'data:'), 404);

        abort_unless(
            preg_match('/^data:(?<mime>[-\w.]+\/[-+\w.]+);base64,(?<data>.*)$/s', $mediaPath, $matches) === 1,
            404
        );

        $contents = base64_decode($matches['data'], true);

        abort_if($contents === false, 404);

        return response($contents, 200, [
            'Content-Type' => $matches['mime'],
            'Content-Length' => (string) strlen($contents),
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
