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
                    'language_id' => $frontContent->language_id,
                ],
                'back_content' => [
                    'id' => $backContent->id,
                    'content' => $backContent->content,
                    'language_id' => $backContent->language_id,
                ],
            ],
        ], 201);
    }
}
