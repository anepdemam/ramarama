<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ChatController extends Controller
{
    public function message(Request $request)
    {
        $message = strtolower($request->input('message', ''));

        // Fetch products for dynamic info
        $products = Product::all();
        $productList = $products->pluck('name')->toArray();

        // Comprehensive response dictionary
        $dictionary = [
            'greeting' => [
                'keywords' => ['hello', 'hi', 'hey', 'yo', 'sup', 'morning', 'evening'],
                'responses' => [
                    "Hi there! I'm Rama, your personal style guide. Ready to break some boundaries today?",
                    "Hey! Rama here. Looking for some armor for your style journey?",
                    "Welcome back! How can I assist with your collection today?",
                    "Yo! Ready to define the culture? What's on your mind?"
                ]
            ],
            'philosophy' => [
                'keywords' => ['philosophy', 'about', 'brand', 'who', 'vision', 'insecurities', 'armor', 'story'],
                'responses' => [
                    "Ramarama co. is about breaking the silence of insecurity. We believe style is your armor against the world. 'Break the wall, define the culture.'",
                    "We exist for those who want to stand out. Our goal is to build 'A World Without Insecurities' through bold, premium streetwear."
                ]
            ],
            'sizing' => [
                'keywords' => ['size', 'sizing', 'fit', 'measurement', 'small', 'medium', 'large', 'xl', 'oversized', 'boxy'],
                'responses' => [
                    "Our pieces generally follow a standard streetwear fit (slightly oversized). Check the fabric weight in the description—heavier usually means a more structured boxy fit!",
                    "Looking for that perfect fit? We recommend going true to size for a relaxed look, or one size down for a more standard fit. Our 'High-Density' tees are especially boxy."
                ]
            ],
            'shipping' => [
                'keywords' => ['shipping', 'delivery', 'arrive', 'track', 'malaysia', 'free', 'courier', 'status'],
                'responses' => [
                    "We provide free shipping within Malaysia! Orders usually ship within 2-3 business days. You'll receive a tracking code via email once it's out.",
                    "Expect your package in 3-5 business days for West Malaysia. East Malaysia takes slightly longer. All shipped with premium care."
                ]
            ],
            'drops' => [
                'keywords' => ['drop', 'new', 'latest', 'release', 'arrival', 'launch', 'upcoming', 'next'],
                'responses' => [
                    "Our drops are curated and limited. Best way to stay updated is to sign up for our newsletter or follow us on Instagram @ramarama.co!",
                    "We release collections in small, intentional batches. Keep an eye on our 'Latest' section—blink and you might miss it!"
                ]
            ],
            'products' => [
                'keywords' => ['product', 'collection', 'clothes', 'stock', 'available', 'buy', 'shop', 'pants', 't-shirt', 'hoodie'],
                'responses' => [
                    "We currently have " . count($productList) . " unique pieces in our vault. Would you like me to tell you about our " . ($productList[0] ?? 'featured') . " collection?",
                    "From heavyweight tees to technical outerwear, every piece is designed to be your armor. You can explore everything in the shop."
                ]
            ],
            'returns' => [
                'keywords' => ['return', 'exchange', 'refund', 'wrong', 'change', 'policy'],
                'responses' => [
                    "We offer easy returns within 7 days of receiving your order. Just make sure the tags are intact and items are unworn!",
                    "Need a different size? We can process exchanges within 7 days. Reach out to support@ramarama.co to start the process."
                ]
            ],
            'contact' => [
                'keywords' => ['contact', 'email', 'support', 'help', 'reach', 'instagram', 'dm', 'talk'],
                'responses' => [
                    "You can reach our team at support@ramarama.co or shoot us a DM on Instagram for a quicker response!",
                    "Always here to help. For order-specific questions, please include your order number in an email to support@ramarama.co."
                ]
            ],
            'price' => [
                'keywords' => ['price', 'cost', 'expensive', 'cheap', 'discount', 'sale', 'promo', 'voucher'],
                'responses' => [
                    "We believe in premium quality at fair pricing. Our materials are sourced for durability, ensuring your armor lasts for years, not months.",
                    "Quality has a price, but we keep it fair. Look out for occasional exclusive drops with special loyalty pricing."
                ]
            ],
            'thanks' => [
                'keywords' => ['thanks', 'thank', 'cool', 'awesome', 'great', 'wow', 'good', 'nice'],
                'responses' => [
                    "Anytime! Stay bold.",
                    "Happy to help. Break the wall!",
                    "You got it. Anything else you need for your collection?",
                    "No problem! Armor up."
                ]
            ]
        ];

        // Better Matching Logic
        $matchedCategory = null;
        $maxScore = 0;

        foreach ($dictionary as $category => $data) {
            $score = 0;
            foreach ($data['keywords'] as $keyword) {
                if (str_contains($message, $keyword)) {
                    $score++;
                    // Exact word match bonus
                    if (preg_match('/\b' . preg_quote($keyword, '/') . '\b/', $message)) {
                        $score += 2;
                    }
                }
            }

            if ($score > $maxScore) {
                $maxScore = $score;
                $matchedCategory = $category;
            }
        }

        // Handle Product Specifics
        foreach ($products as $product) {
            if (str_contains($message, strtolower($product->name))) {
                return response()->json([
                    'response' => "The **{$product->name}** is a standout piece! It's currently RM{$product->price} and it's perfect if you're looking for something that says {$product->description}. Shall I help you find your size?",
                    'status' => 'success'
                ]);
            }
        }

        // Match Categorized Responses
        if ($matchedCategory && $maxScore > 0) {
            $categoryResponses = $dictionary[$matchedCategory]['responses'];
            $response = $categoryResponses[array_rand($categoryResponses)];
        }
        // Handle Product Specifics
        else {
            foreach ($products as $product) {
                if (str_contains($message, strtolower($product->name))) {
                    $response = "The **{$product->name}** is a standout piece! It's currently RM{$product->price} and it's perfect if you're looking for something that says {$product->description}. Shall I help you find your size?";
                    $matchedCategory = 'product'; // Flag for status
                    break;
                }
            }
        }

        // Final Fallback
        if (!$response) {
            $response = "That's an interesting question! I'm still learning the nuances of our culture, but I can definitely help with sizing, shipping, product info, or our brand philosophy. What's on your mind?";
        }

        return response()->json([
            'response' => $response,
            'status' => 'success'
        ]);
    }
}
