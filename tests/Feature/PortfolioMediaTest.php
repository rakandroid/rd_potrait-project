<?php

namespace Tests\Feature;

use App\Models\PortfolioPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_base64_portfolio_media_is_served_through_a_small_page_url(): void
    {
        $photo = PortfolioPhoto::create([
            'title' => 'Tiny Portrait',
            'category' => 'Portrait',
            'placement' => 'portfolio',
            'media_type' => 'image',
            'image_path' => 'data:image/png;base64,'.base64_encode('image-bytes'),
            'sort_order' => 0,
            'is_visible' => true,
            'show_in_hero' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('portfolio.media', ['portfolioPhoto' => $photo, 'field' => 'image']))
            ->assertDontSee('data:image/png;base64');
    }

    public function test_base64_portfolio_media_endpoint_returns_the_decoded_file(): void
    {
        $photo = PortfolioPhoto::create([
            'title' => 'Tiny Portrait',
            'category' => 'Portrait',
            'placement' => 'portfolio',
            'media_type' => 'image',
            'image_path' => 'data:image/png;base64,'.base64_encode('image-bytes'),
            'sort_order' => 0,
            'is_visible' => true,
        ]);

        $this->get(route('portfolio.media', ['portfolioPhoto' => $photo, 'field' => 'image']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertContent('image-bytes');
    }
}
