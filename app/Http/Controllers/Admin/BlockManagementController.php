<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockInstance;
use App\Models\BlockType;
use Illuminate\Http\Request;

class BlockManagementController extends Controller
{
    /**
     * Display the unified block library.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $typeFilter = $request->get('type');

        $query = BlockInstance::with('blockType')->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($typeFilter) {
            $query->where('block_type_id', $typeFilter);
        }

        $blocks = $query->paginate(20)->withQueryString();
        $types = BlockType::orderBy('is_system', 'desc')->orderBy('name')->get();

        return view('admin.block-management.index', compact('blocks', 'types'));
    }
}
