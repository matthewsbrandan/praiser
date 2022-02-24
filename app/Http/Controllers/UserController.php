<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Exception;

use App\Models\User;
use App\Models\Ability;
use App\Models\Ministry;
use App\Models\UserAbility;
use App\Models\UserMinistry;
use App\Models\PasswordReset;
use App\Services\SenderService;

class UserController extends Controller
{
    protected $take;

    public function __construct(){
        $this->take = 20;
    }
    public function index($skip = 0, $json = false){
        if(auth()->user()->type != 'dev') return redirect()->back()->with(
            'message',
            'Você não tem permissão de acessar essa página'
        );
        $users = User::get();
        return view('user.index',['users' => $users]);
    }
    public function create($email = null){
        if(auth()->user() && auth()->user()->password) return redirect()->back()->with(
            'message',
            'Para realizar um novo cadastro primeiro efetue o logout'
        );
        if(!auth()->user()){
            if(!$email) return redirect()->route('login')->with(
                'message',
                'Preencha seu email'
            );
            else if(User::whereEmail($email)->first()) return redirect()->route('login')->with(
                'message',
                'Este email já está sendo utilizado'
            );
        }
        
        $abilities = Ability::get();
        $ministries = Ministry::get();

        $user = auth()->user() ? (object)[
            'profile' => auth()->user()->profile,
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]:(object)[
            'profile' => User::getDefaultProfile(),
            'name' => null,
            'email' => $email,
        ];

        return view('user.create.index', [
            'user' => $user,
            'abilities' => $abilities,
            'ministries' => $ministries
        ]);
    }
    public function store(Request $request){
        $data = [
            "name" => $request->name,
            "whatsapp" => $request->whatsapp,
            "password" => bcrypt($request->password),
            "availability" => $request->availability,
            "outhers_availability" => $request->outhers_availability,    
        ];
        
        if(auth()->user()){
            auth()->user()->update($data);
        }else{
            $data+= ["email" => $request->email];
            if(User::whereEmail($request->email)->first()) return redirect()
                ->route('login')
                ->with('message','Este email já está sendo utilizado.');

            if(!$user = User::create($data)) return redirect()
                ->back()
                ->with('message','Houve um erro ao criar seu usuário');

            Auth::login($user, true);
        }

        try{
            $errors = [];
            if($request->file('profile')){
                ['names' => $names,'errors' => $errors] = $this->uploadImages(
                    [$request->file('profile')],
                    "uploads/profile/" . auth()->user()->id . "/"
                );
                
                if(count($errors) > 0 || count($names) == 0) $errors[] = "Não foi possível salvar a imagem";
                else auth()->user()->update([
                    'profile' => $names[0]
                ]);
            }

            if($request->abilities){
                $abilitiesFails = count($request->abilities);
                foreach($request->abilities as $ability){
                    if(Ability::whereId($ability)->first()){
                        $data = [
                            'user_id' => auth()->user()->id,
                            'ability_id' => $ability,
                        ];
                        if(UserAbility::updateOrCreate($data,$data)) $abilitiesFails--;
                    }
                }
                if($abilitiesFails > 0){
                    $errors[] = $abilitiesFails == 1 ? "1 habilidade não foi cadastrada":
                        $abilitiesFails." habilidades não foram cadastradas";
                }
            }
            if($request->ministries_ids){
                $ministries = array_filter(explode(',',$request->ministries_ids),function($ministry){
                    return !!$ministry && strlen($ministry) > 0;
                });
                $ministriesFails = count($ministries);
                $ministry_active = null;
                foreach($ministries as $ministry){
                    if($thisMinistry = Ministry::whereId($ministry)->first()){
                        $data = [
                            'user_id' => auth()->user()->id,
                            'ministry_id' => $ministry,
                        ];
                        if(UserMinistry::updateOrCreate($data, $data + [
                            'status' => $thisMinistry->free_entry ? 'active':'disabled'
                        ])){
                            $ministriesFails--;
                            if(!$ministry_active) $ministry_active = $ministry;
                        }
                    }
                }
                if($ministry_active) auth()->user()->selectMinistry($ministry_active);
                if($ministriesFails > 0){
                    $errors[] = $ministriesFails == 1 ? "1 ministério não pode ser cadastrado":
                        $ministriesFails." ministérios não puderam ser cadastrados";
                }
            }
        }catch(Exception $e){
            $errors[] = "Houve um erro ao salvar algumas de suas informações. Verifique os dados do seu perfil para se certificar de quais informações estão faltantes";
        }

        if(count($errors) > 0){
            $message = "Alguns erros foram encontrados no cadastro: <br/>" . implode('<br/>', $errors);
            return redirect()
                ->route('home')
                ->with('welcome', auth()->user()->name.',<br/>Bem vindo ao Praiser')
                ->with('message', $message);
        }

        return redirect()
            ->route('home')
            ->with('welcome',auth()->user()->name.',<br/>Bem vindo ao Praiser');
    }
    public function profile($email = null){
        if($email){
            if(!$user = User::whereEmail($email)->first()) return redirect()->back()->with(
                'message',
                'Usuário não encontrado'
            );
        }
        else $user = auth()->user();

        $abilities = Ability::get();
        return view('user.profile.index',[
            'user' => $user,
            'abilities' => $abilities
        ]);
    }
    public function edit(){
        $view = $this->checkStatus();
        if($view) return $view;

        return view('profile.edit');
    }
    public function update(Request $request){
        $data = ['name' => $request->name];
        auth()->user()->update($data);
        return redirect()->route('profile.edit')->with(
            'message','Dados alterados com sucesso'
        );
    }
    public function updateAvailability(Request $request){
        auth()->user()->update([
            'availability' => $request->availability,
            'outhers_availability' => $request->outhers_availability
        ]);

        return redirect()->route('user.profile')->with(
            'message','Disponibilidade alterada com sucesso'
        );
    }
    public function delete($id){
        $this->adminOnly();

        if(auth()->user()->type === 'admin') $user = User::whereId($id)
            ->where('type','user')
            ->first();
        else if(auth()->user()->type === 'dev') $user = User::whereId($id)
            ->first();

        if(!$user) return redirect()
            ->back()
            ->with('message','Usuário não encontrado');

        $user->delete();

        return redirect()->route('users.index')->with('message','Usuário excluído com sucesso!');
    }
    public function status($user_id, $status){
        $this->adminOnly();

        if(!in_array($status,['active','disabled'])) return redirect()
            ->back()
            ->with('message', 'Status inválido');
        if(!$user = User::whereId($user_id)->first()) return redirect()
            ->back()
            ->with('message', 'Usuário não localizado!');

        $user->update(['status' => $status]);

        return redirect()->route('users.index')->with('message', 'Status atualizado com sucesso!');
    }
    public function loginWith($user_id){
        $this->adminOnly();

        if(auth()->user()->type === 'admin') $user = User::whereId($user_id)
            ->where('type', 'user')
            ->first();
        else if(auth()->user()->type === 'dev') $user = User::whereId($user_id)
            ->first();

        if(!$user) return redirect()
            ->back()
            ->with('message','Usuário não localizado!');

        $tunel = $this->handleOpenTunel();
        auth()->user()->update(['tunel' => $tunel]);
        $tunel = auth()->user()->email.','.$tunel;

        Auth::login($user);
        return redirect()->route('index')->with('open-tunel', $tunel);
    }
    public function tunel($tunel){
        if(!$user = User::whereTunel($tunel)->first()) return redirect()->back()->with(
            'notify',
            'Tunel fechado'
        )->with('notify-type','danger')->with('close-tunel',true);
        
        $user->closeTunel();
        Auth::login($user, true);
        return redirect()->route('index')->with('close-tunel', true);
    }
    public function changePassword(Request $request){
        if(!Hash::check($request->current_password,auth()->user()->password)) return redirect()
            ->route('profile.edit')
            ->with('change-password-error-type','current-password');
        
        auth()->user()->update(['password' => bcrypt($request->password)]);

        return redirect()
            ->route('profile.edit')
            ->with('message','Senha alterada com sucesso!');
    }
    // BEGIN:: FUNCTIONS FORGOT_PASSWORD
    public function forgotPassword(Request $request){
        if(PasswordReset::whereEmail($request->email)->first()) return redirect()
            ->route('index')
            ->with('message','Já foi solicitada um redefinição de senha verifique seu email.');

        $token = Str::random(8);
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token
        ]);

        $sender = new SenderService();
        $response = $sender->send("send/forgot-password",[
            'to_address' => $request->email,
            'link' => route('redefine-password.index',[
                'email' => $request->email,
                'token' => $token
            ])."#redefine-password"
        ]);
        return redirect()->route('index')->with('message',
            $response['result'] ?
            'Solicitação de redefinição de senha enviada com sucesso. Olhe em sua caixa de email e finalize sua recuperação':
            $response['response']
        );
    }
    public function redefinePassword($email, $token){
        if(!$pass = PasswordReset::with('user')
            ->whereEmail($email)
            ->whereToken($token)
            ->first()
        ) return abort(404);
        
        return view('welcome',[
            'formActive' => 'redefine-password',
            'name' => explode(' ',$pass->user->name)[0],
            'email' => $email,
            'token' => $token
        ]);
    }
    public function storePassword(Request $request){
        if(!PasswordReset::whereEmail($request->email)->first()) return view('welcome',[
            'message' => 'Não há nenhuma solicitação de redefinição de senha para este email!'
        ]);
        if(!$pass = PasswordReset::with('user')
            ->whereEmail($request->email)
            ->whereToken($request->token)
            ->first()
        ) return view('welcome',[
            'message' => 'Token de redefinição de senha inválido'
        ]);
        $pass->user->update(["password" => bcrypt($request->password)]);

        Auth::login($pass->user);

        PasswordReset::whereEmail($request->email)
            ->whereToken($request->token)
            ->delete();
            
        return redirect()->route('home')->with('message','Senha alterada com sucesso!');
    }
    // END:: FUNCTIONS FORGOT_PASSWORD | BEGIN:: LOCAL FUNCTIONS
    protected function generateAccessToken(){        
        do{ $access_token = Str::random(15); }
        while(User::whereAccessToken($access_token)->first());
        
        return $access_token;
    }
    protected function handleOpenTunel(){
        do{ $tunel = Str::random(15); }
        while(User::whereTunel($tunel)->first());
        
        return $tunel;
    }
    // END:: LOCAL FUNCTIONS
}