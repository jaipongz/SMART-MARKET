<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use App\Models\PlyerModel;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="API documentation for the application",
 *     @OA\Contact(
 *         email="support@example.com"
 *     )
 * )
 */
class APIController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticate user and return JWT token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJK...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $user = Admin::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate JWT token
        $payload = [
            'iss' => "your-application-name", // Issuer of the token
            'sub' => $user->id, // Subject of the token (user ID)
            'iat' => time(), // Time token was issued
            'exp' => time() + 60 * 60 // Token expiration time (1 hour)
        ];

        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Get(
     *     path="/api/getItems",
     *     summary="Retrieve player items",
     *     description="Fetch items associated with a specific player ID.",
     *     tags={"Items"},
     *     @OA\Parameter(
     *         name="playerId",
     *         in="query",
     *         required=true,
     *         description="The ID of the player whose items are being retrieved.",
     *         @OA\Schema(type="string", example="xxxxxxxx")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Items retrieved successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="itemId", type="integer", example=1),
     *                     @OA\Property(property="itemName", type="string", example="Sword of Light"),
     *                     @OA\Property(property="quantity", type="integer", example=1),
     *                     @OA\Property(property="rarity", type="string", example="legendary"),
     *                     @OA\Property(property="equipped", type="boolean", example=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="playerId is required.")
     *         )
     *     )
     * )
     */
    public function getItems(Request $request)
    {
        $request->validate([
            'playerId' => 'required|string',
        ]);

        $items = [
            [
                "itemId" => 1,
                "itemName" => "Sword of Light",
                "quantity" => 1,
                "rarity" => "legendary",
                "equipped" => true,
            ],
            [
                "itemId" => 2,
                "itemName" => "Healing Potion",
                "quantity" => 5,
                "rarity" => "common",
                "equipped" => false,
            ],
        ];

        return response()->json([
            "status" => "success",
            "items" => $items,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/updatePlayerData",
     *     summary="Update or insert player data",
     *     description="Update existing player data or insert a new record if the player does not exist.",
     *     tags={"Player"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"playerId", "role", "rank", "race", "clan", "money"},
     *             @OA\Property(property="playerId", type="string", example="123456"),
     *             @OA\Property(property="role", type="string", example="Warrior"),
     *             @OA\Property(property="rank", type="string", example="Elite"),
     *             @OA\Property(property="race", type="string", example="Elf"),
     *             @OA\Property(property="clan", type="string", example="Shadow Clan"),
     *             @OA\Property(property="money", type="string", example="10000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player data has been updated or inserted successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player data has been updated or inserted successfully!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error: playerId is required.")
     *         )
     *     )
     * )
     */
    public function updatePlayerData(Request $request)
    {
        $request->validate([
            'playerId' => 'required|string',
            'role' => 'required|string',
            'rank' => 'required|string',
            'race' => 'required|string',
            'clan' => 'required|string',
            'money' => 'required|string',
        ]);

        $playerId = $request->input('playerId');
        $player = PlyerModel::find($playerId);

        if ($player) {
            $player->role = $request->input('role');
            $player->rank = $request->input('rank');
            $player->race = $request->input('race');
            $player->clan = $request->input('clan');
            $player->money = $request->input('money');
            $player->last_active = Carbon::now();
            $player->save();
        } else {
            $player = new PlyerModel([
                'playerId' => $request->input('playerId'),
                'role' => $request->input('role'),
                'rank' => $request->input('rank'),
                'race' => $request->input('race'),
                'clan' => $request->input('clan'),
                'money' => $request->input('money'),
                'last_active' => Carbon::now()

            ]);
            $player->save();
        }

        return response()->json(['message' => 'Player data has been updated or inserted successfully!'], 200);
    }
}