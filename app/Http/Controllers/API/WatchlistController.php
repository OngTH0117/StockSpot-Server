<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Watchlist;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WatchlistController extends Controller
{
    public function addToWatchlist(Request $request)
    {
        $user = $request->user();
        $stockIdentifier = $request->input('stockIdentifier');

        // Check if the stock is already in the user watchlist
        $existingWatchlist = Watchlist::where([
            'user_id' => $user->id,
            'stock_identifier' => $stockIdentifier,
        ])->first();

        if (!$existingWatchlist) {
            // Add the stock to the user watchlist
            $watchlist = new Watchlist();
            $watchlist->user_id = $user->id;
            $watchlist->stock_identifier = $stockIdentifier;
            
            $watchlist->save();
        }

        return response()->json(['message' => 'Stock added to watchlist']);
    }
    
    public function getUserWatchlist($userId)
    {
        $watchlist = Watchlist::where('user_id', $userId)->get();

        return response()->json($watchlist);
    }

    public function removeFromWatchlist(Request $request, $watchlistId)
    {
        // Find the watchlist item by ID
        $watchlistItem = Watchlist::find($watchlistId);

        if (!$watchlistItem) {
            return response()->json(['message' => 'Watchlist item not found'], 404);
        }
       
        if ($watchlistItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $watchlistItem->delete();

        return response()->json(['message' => 'Item removed from watchlist']);
    }
}
