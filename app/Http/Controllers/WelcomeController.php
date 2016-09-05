<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\ContactFormRequest;

use App\Http\Requests, View, Validator, Input, Session, Redirect, Auth, Hash,Mail;
use App\User;

class WelcomeController extends Controller
{


    public function login()
    {
        return View::make('pages.login');
    }


    public function register()
    {
        return View::make('pages.register');
    }

    public function doRegister()
    {

        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:6|confirmed',
            'password_confirmation' => 'required|min:3'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('register')
                ->withErrors($validator);
        } else {

            $user = new User;
            $user->email = Input::get('email');
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            
            Session::flash('message', 'Registered!');
            return Redirect::to('/login');

        }


    }

    public function doLogin()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:6'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            Session::flash('message', 'Login failed!');
            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            if (Auth::attempt($userdata)) {

                Session::save();
                echo 'Welcome to our shop!';
                return redirect()->intended('/');


            } else {

                Session::flash('alert-danger', 'Login failed! Please check your credentials');
                return Redirect::to('login');
            }
        }
    }


    public function logout()
    {
        Auth::logout();
        return Redirect::to('login');
    }

    public function contact()
    {

        return View::make('pages.contact');

    }

    public function sendEmail(ContactFormRequest $request)
    {

        Mail::send('emails.contact',
            array(
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'message' => $request->get('message')
            ), function($message)
            {
                $message->from(env('MAIL_FROM'));
                $message->to(env('MAIL_TO'), env('MAIL_NAME'))->subject('Contact Form');
            });

        return Redirect::to('contact')->with('message', 'Thanks for contacting us!');


    }




}