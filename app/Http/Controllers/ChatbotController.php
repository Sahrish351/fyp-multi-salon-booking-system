<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
 
class ChatbotController extends Controller
{
    /**
     * Handles a chat message from the "Bella" widget on the homepage,
     * sends it to Google Gemini (free tier) with context about
     * Beauty Blush Salons, and returns the AI's reply as JSON.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);
 
        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');
 
        if (!$apiKey) {
            return response()->json([
                'reply' => "Bella is temporarily unavailable — please try again shortly.",
            ], 200);
        }
 
        // System context: keeps the AI on-topic and gives it real
        // knowledge about the platform, so it doesn't hallucinate
        // unrelated things or answer wildly off-brand questions.
        $systemContext = <<<EOT
You are "Bella", the friendly AI assistant for Beauty Blush Salons — a
multi-tenant salon booking platform (like a Fresha-style marketplace)
where clients can browse salons, book appointments, and salon owners
can list their business and manage bookings.
 
Key facts about the platform:
- Roles: Admin, Salon Owner, Client.
- Salon owner flow: they fill their salon details to register -> Admin reviews and approves the salon -> only after approval do they get access to their salon dashboard (services, stylists, time slots, appointments, waitlist, payments, sales analytics, clients, reviews, gallery, notifications).
- Booking flow: Choose Salon -> Choose Service -> Choose Stylist -> Choose Date & Time -> Payment -> Confirmation.
- IMPORTANT: An appointment is only confirmed after the client pays a Rs. 100 advance booking fee. Without this advance payment, the slot is NOT reserved.
- Clients can join a Waitlist if a slot is full, and get notified when one opens.
- Payment methods: EasyPaisa, JazzCash, UBL Bank transfer.
- Browsing salons is completely free. Booking requires a Rs. 100 advance to confirm the slot; the rest of the service cost is paid separately. Salon owners can join on a free plan.
- Clients can leave reviews/ratings and file complaints after appointments.
- Every salon has its own profile page with services, team, gallery, and map location.
 
Reply style: SHORT and to the point — 1 to 2 sentences maximum, no filler,
no repeating the same information across sentences. Friendly but direct,
like a quick helpful reply, not a paragraph. Always on-topic about
Beauty Blush Salons. If asked something totally unrelated to the platform
(e.g. politics, homework, coding help), politely redirect back in one
short sentence. Never make up specific salon names or data you don't
actually have — speak generally about how the platform works.
EOT;
 
        try {
            $response = Http::timeout(25)->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key=' . $apiKey,
                [
                    'system_instruction' => [
                        'parts' => [['text' => $systemContext]],
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => $userMessage]],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.5,
                        'maxOutputTokens' => 80,
                    ],
                ]
            );
 
            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text']
                    ?? "Sorry, I couldn't quite understand that — could you rephrase?";
 
                return response()->json(['reply' => trim($reply)]);
            }
 
            // Rate limit or other API error — fail gracefully.
            Log::warning('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
 
            return response()->json([
                'reply' => "I'm a little busy right now — please try again in a moment!",
            ]);
 
        } catch (\Exception $e) {
            Log::error('Gemini API exception: ' . $e->getMessage());
 
            return response()->json([
                'reply' => "Something went wrong on my end — please try again shortly.",
            ]);
        }
    }
}
 