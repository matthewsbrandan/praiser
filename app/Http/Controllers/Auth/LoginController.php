<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request){
        if(Auth::check()) Auth::logout();

        if($request->google_id) return $this->handleLoginWithGoogle($request);

        if(!User::whereEmail($request->email)->first()) return redirect()
            ->route('login')
            ->with('notify','Email não encontrado')
            ->with('notify-type','danger');

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, true)) return redirect()
            ->route('login')
            ->with('notify','Senha inválida')
            ->with('notify-type','danger');

        auth()->user()->closeTunel();
        
        return redirect()->route('home');
    }

    public function login(){ return view('login'); }
    public function authenticateEmail(Request $request){
        if(!$user = User::whereEmail($request->email)->first()) return redirect()
            ->route('register',['email' => $request->email]);
        if(!$user->password) return redirect()->route('index')->with(
            'message',
            'Você ainda não possui senha cadastrada, efetue o login com o google e cadastre sua primeira senha'
        );
        return view('login',['email' => $request->email]);
    }
    public function authenticatePhone(Request $request){
        if(Auth::check()) Auth::logout();

        if(!$user = User::whereWhatsapp($request->whatsapp)->first()) return redirect()
            ->route('login')
            ->with('notify','Nº de Whatsapp não encontrado')
            ->with('notify-type','danger');
        
        $credentials = $request->only('whatsapp', 'password');

        if (!Auth::attempt($credentials, true)) return redirect()
            ->route('login')
            ->with('notify','Senha inválida')
            ->with('notify-type','danger');

        auth()->user()->closeTunel();
        
        return redirect()->route('home');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('index');
    }

    protected function handleLoginWithGoogle(Request $request){
        if($user = User::whereEmail($request->email)->first()){
            if($user->google_id == $request->google_id){
                $user->closeTunel();
                Auth::login($user, true);
                return redirect()->route('home');
            }else return redirect()->back()->with(
                'message', 'Erro de autenticação, Id do google inválido'
            );
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'profile' => $request->profile ?? null,
            'google_id' => $request->google_id,
        ]);
        Auth::login($user, true);

        return redirect()->route('register')->with(
            'message', 'Faltam apenas alguns passos para finalizar o seu cadastro'
        );
    }
}