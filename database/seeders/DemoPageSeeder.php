<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlockType;
use App\Models\BlockInstance;
use App\Models\Page;
use App\Models\PageLayout;

class DemoPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get Block Types
        $heroType = BlockType::where('type_key', 'hero_canvas')->first();
        $bentoType = BlockType::where('type_key', 'bento_grid')->first();
        $matrixType = BlockType::where('type_key', 'feature_matrix')->first();
        $scrollerType = BlockType::where('type_key', 'product_scroller')->first();
        $splitType = BlockType::where('type_key', 'split_banner')->first();
        $faqType = BlockType::where('type_key', 'faq_accordion')->first();

        if (!$heroType) return;

        // 2. Create the "Stitch Showcase" Page
        $page = Page::create([
            'slug' => 'stitch-showcase',
            'title' => 'Stitch Design System | Mastermind Showcase',
            'is_published' => true,
        ]);

        // 3. Create Instances and Layouts
        
        // Block 1: Hero
        $i1 = BlockInstance::create([
            'block_type_id' => $heroType->id,
            'name' => 'Demo: Hero Canvas',
            'settings' => [
                'headline' => "Engineering the\nFuture of EC",
                'lead_text' => 'A masterclass in cinematic storefront design and advanced fiscal globalization.',
                'bg_image' => 'https://images.unsplash.com/photo-1635776062127-d379bfcba9f8?auto=format&fit=crop&q=80&w=2000',
                'cta_primary_label' => 'Explore the Library',
                'cta_secondary_label' => 'Technical Specs',
                'overlay_opacity' => 0.6,
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i1->id, 'section_key' => 'hero', 'sort_order' => 1]);

        // Block 2: Bento
        $i2 = BlockInstance::create([
            'block_type_id' => $bentoType->id,
            'name' => 'Demo: Bento Marketing',
            'settings' => [
                'title' => 'Modular Intelligence',
                'subtitle' => 'The Stitch Architecture',
                'item_1_title' => 'Global Tax Mastery',
                'item_1_text' => 'Region-aware fiscal rules that synchronize with your regional timezones automatically.',
                'item_1_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=80&w=800',
                'item_2_title' => 'Responsive Core',
                'item_2_text' => '100% mobile-first layouts with zero compromise on visual fidelity.',
                'item_3_title' => 'AI Predication',
                'item_4_title' => '99.9% Uptime',
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i2->id, 'section_key' => 'main', 'sort_order' => 2]);

        // Block 3: Feature Matrix
        $i3 = BlockInstance::create([
            'block_type_id' => $matrixType->id,
            'name' => 'Demo: Feature Matrix',
            'settings' => [
                'title' => 'Enterprise Grade Capabilities',
                'f1_title' => 'Multi-Language', 'f1_desc' => 'Native i18n support across all administrative and storefront surfaces.',
                'f2_title' => 'Secure Auth', 'f2_desc' => 'Multi-factor authentication and role-based access control.',
                'f3_title' => 'Real-time Analytics', 'f3_desc' => 'Live performance monitoring with predictive trend analysis.',
                'f4_title' => 'Stitch Designer', 'f4_desc' => 'Advanced layout builder with real-time browser synchronization.',
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i3->id, 'section_key' => 'main', 'sort_order' => 3]);

        // Block 4: Product Scroller
        $i4 = BlockInstance::create([
            'block_type_id' => $scrollerType->id,
            'name' => 'Demo: Product Scroller',
            'settings' => [
                'title' => 'Curated Collections',
                'limit' => 6,
                'view_all_link' => '#',
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i4->id, 'section_key' => 'main', 'sort_order' => 4]);

        // Block 5: Split Banner
        $i5 = BlockInstance::create([
            'block_type_id' => $splitType->id,
            'name' => 'Demo: Split Banner',
            'settings' => [
                'headline' => 'Design as a Competitive Advantage.',
                'description' => 'In a world of generic storefronts, your brand deserves a cinematic presence that resonates on every pixel.',
                'image_url' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?auto=format&fit=crop&q=80&w=1200',
                'button_text' => 'Start Your Journey',
                'button_url' => '#',
                'alignment' => 'left',
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i5->id, 'section_key' => 'main', 'sort_order' => 5]);

        // Block 6: FAQ
        $i6 = BlockInstance::create([
            'block_type_id' => $faqType->id,
            'name' => 'Demo: FAQ',
            'settings' => [
                'title' => 'Deep Dive Support',
                'q1' => 'How customizable are the blocks?',
                'a1' => 'Every parameter—from typography to background opacity—is configurable via the admin dashboard.',
                'q2' => 'Do they handle dynamic content?',
                'a2' => 'Yes, blocks like the Product Scroller synchronize with your live inventory in real-time.',
                'q3' => 'Is it production ready?',
                'a3' => 'Absolutely. Every component is optimized for performance and full cross-browser compatibility.',
            ],
            'is_active' => true,
        ]);
        PageLayout::create(['page_id' => $page->id, 'block_instance_id' => $i6->id, 'section_key' => 'main', 'sort_order' => 6]);
    }
}
