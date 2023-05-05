<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function inscription(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password),
        ]);
        //gestion des erreurs
        if (!$user) {
            return response()->json([
                'message' => 'Une erreur est survenue lors de la création de l\'utilisateur',
            ], 500);
        }
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
        ], 201);

    }
    public function connexion(Request $request){
        $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', strtolower($request->email))->first();
        if (!$user || !password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants incorrects',
            ], 401);
        }
        else{
        $token = $user->createToken('token')->plainTextToken;
        return response([
            'message' => 'Connexion réussie',
            'token' => $token,
        ], 200);}

    }
    public function deconnexion(){
        auth()->user()->tokens()->each(function($token, $key){
            $token->delete();
        });
        return response()->json([
            'message' => 'Déconnexion réussie',
        ], 200);
      
       
    }
    public function suppression(Request $request){
        $request->validate([
            'password' => 'required|string|min:8',
            'id' => 'required|integer'
        ]);
        $user = auth()->user();
        if (!password_verify($request->password, $user->password)) {
            return response()->json([
                'message' => 'Mot de passe incorrect',
            ], 401);
        }
        if($request->id != $user->id){
            return response(array(
                "message"=>"vous n'avez pas le droit"
            ));
        }
        else{
        $user->delete();
        return response()->json([
            'message' => 'Compte supprimé avec succès',
        ], 200);}
       
}

}