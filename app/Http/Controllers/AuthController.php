<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="Operaciones relacionadas con la autenticación de usuarios"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registra un nuevo usuario",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="user@example.com", description="Correo del usuario"),
     *             @OA\Property(property="password", type="string", example="password123", description="Contraseña"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123", description="Confirmación de contraseña"),
     *             @OA\Property(property="role", type="string", example="customer", enum={"customer", "employee", "admin"}, description="Rol del usuario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario registrado correctamente"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:customer,employee,admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = new User();
        $user->email = $request->email;
        $user->password_hash = Hash::make($request->password);
        $user->role = $request->role;
        $user->is_active = true;
        $user->save();

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Inicia sesión y genera un token JWT",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="user@example.com", description="Correo del usuario"),
     *             @OA\Property(property="password", type="string", example="password123", description="Contraseña")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="token", type="string", description="Token JWT"),
     *             @OA\Property(property="expires_in", type="integer", description="Tiempo de expiración en segundos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciales incorrectas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No se pudo generar el token")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo generar el token: ' . $e->getMessage()], 500);
        }

        $ttlSeconds = config('jwt.ttl') * 60;
        $expiresAt = Carbon::now()->addSeconds($ttlSeconds);

        $tokenHash = hash('sha256', $token);
        DB::table('authtokens')->insert([
            'user_id' => Auth::user()->id,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'revoked' => false,
            'created_at' => Carbon::now(),
        ]);

        Log::info('Token generado: ' . $token);

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'expires_in' => $ttlSeconds,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cierra la sesión del usuario",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sesión cerrada correctamente")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $tokenHash = hash('sha256', $token);
            DB::table('authtokens')
                ->where('token_hash', $tokenHash)
                ->update(['revoked' => true]);
        }

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obtiene los datos del usuario autenticado",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="role", type="string", example="customer")
     *         )
     *     )
     * )
     */
    public function me()
    {
        return response()->json(Auth::user());
    }
}