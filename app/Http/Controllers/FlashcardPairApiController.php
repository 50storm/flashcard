<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Flashcard;
use App\Models\Language;
use App\Models\Content;
use App\Models\FlashcardPair;
use Illuminate\Http\Request;

class FlashcardPairApiController extends Controller
{

    // {
    //     "pair": [
    //         {
    //             "frontContent": "Hello World",
    //             "language_code": "en-US",
    //         },
    //         {
    //             "BackContent": "こんにちは",
    //             "language_code": "ja",
    //         }
    //     ]
    // }    
    public function createPair(Request $request, $flashcardId)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'pair' => 'required|array|size:2',
            'pair.*.language_code' => 'required|string',
            'pair.0.frontContent' => 'required|string',
            'pair.1.BackContent' => 'required|string',
        ]);

        $flashcard = Flashcard::findOrFail($flashcardId);

        // TODO エラーのときのJSONレスポンス

        // Retrieve or create language_id for front content based on language_code
        $frontLanguage = Language::where('language_code', $validatedData['pair'][0]['language_code'])->firstOrFail();

        // dd($frontLanguage);

        $frontContent = Content::create([
            'content' => $validatedData['pair'][0]['frontContent'],
            'language_id' => $frontLanguage->id, // Use language_id from Language model
        ]);

        // dd($frontContent);

        // Retrieve or create language_id for back content based on language_code
        $backLanguage = Language::where('language_code', $validatedData['pair'][1]['language_code'])->firstOrFail();

        $backContent = Content::create([
            'content' => $validatedData['pair'][1]['BackContent'],
            'language_id' => $backLanguage->id, // Use language_id from Language model
        ]);

        $flashcardPair = FlashcardPair::create([
            'flashcard_id' => $flashcard->id,
            'front_content_id' => $frontContent->id,
            'back_content_id' => $backContent->id,
        ]);

        // Return the created data in the response
        return response()->json([
            'message' => 'Flashcard pair created successfully',
            'flashcard_pair' => [
                'id' => $flashcardPair->id,
                'flashcard_id' => $flashcard->id,
                'front_content' => [
                    'id' => $frontContent->id,
                    'content' => $frontContent->content,
                    'language_code' => $flashcardPair->frontContent->language->language_code,
                ],
                'back_content' => [
                    'id' => $backContent->id,
                    'content' => $backContent->content,
                    'language_code' => $flashcardPair->frontContent->language->language_code,
                ],
            ],
        ], 201);
    }

    // Get a flashcard pair's details
    public function getPair($pairId)
    {
        $pair = FlashcardPair::with(['frontContent', 'backContent'])->findOrFail($pairId);

        return response()->json([
            'front_content' => [
                'content' => $pair->frontContent->content,
                'language_code' => $pair->frontContent->language->language_code,
            ],
            'back_content' => [
                'content' => $pair->backContent->content,
                'language_code' => $pair->backContent->language->language_code,
            ],
        ]);
    }

        // Update a flashcard pair
        public function updatePair(Request $request, $pairId)
        {
            // Validate the incoming request
            $validatedData = $request->validate([
                'pair' => 'required|array|size:2',
                'pair.*.language_code' => 'required|string',
                'pair.0.frontContent' => 'required|string',
                'pair.1.BackContent' => 'required|string',
            ]);
    
            // Find the flashcard pair
            $flashcardPair = FlashcardPair::with(['frontContent', 'backContent'])->findOrFail($pairId);
    
            // Update front content
            $frontLanguage = Language::where('language_code', $validatedData['pair'][0]['language_code'])->firstOrFail();
            $flashcardPair->frontContent->update([
                'content' => $validatedData['pair'][0]['frontContent'],
                'language_id' => $frontLanguage->id,
            ]);
    
            // Update back content
            $backLanguage = Language::where('language_code', $validatedData['pair'][1]['language_code'])->firstOrFail();
            $flashcardPair->backContent->update([
                'content' => $validatedData['pair'][1]['BackContent'],
                'language_id' => $backLanguage->id,
            ]);
    
            // Reload relationships
            $flashcardPair->load(['frontContent', 'backContent']);
    
            // Return the response with the updated data
            return response()->json([
                'message' => 'Flashcard pair updated successfully',
                'flashcard_pair' => [
                    'id' => $flashcardPair->id,
                    'flashcard_id' => $flashcardPair->flashcard_id,
                    'front_content' => [
                        'id' => $flashcardPair->frontContent->id,
                        'content' => $flashcardPair->frontContent->content,
                        'language_code' => $flashcardPair->frontContent->language->language_code,
                    ],
                    'back_content' => [
                        'id' => $flashcardPair->backContent->id,
                        'content' => $flashcardPair->backContent->content,
                        'language_code' => $flashcardPair->backContent->language->language_code,
                    ],
                ],
            ], 200);
        }

    // ペアの削除
    public function deletePair($pairId)
    {
        $pair = FlashcardPair::findOrFail($pairId);
        $pair->delete();

        return response()->json(['message' => 'Flashcard pair deleted successfully.']);
    }

}
