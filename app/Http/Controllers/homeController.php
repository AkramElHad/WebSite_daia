<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail; // Assurez-vous que cette ligne est correcte

class HomeController extends Controller
{
    /**
     * Show the application welcome page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Send the contact form data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendContact(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'message' => 'required|string|max:1000',
    ]);

    $details = [
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'message' => $request->message,
    ];

    try {
        Mail::to('akram.elhadhoudi@daia-technologie.ma')->send(new ContactMail($details));
        return back()->with('success', 'Votre message a été envoyé avec succès.');
    } catch (\Exception $e) {
        return back()->with('error', 'Une erreur s\'est produite lors de l\'envoi de votre message. Veuillez réessayer plus tard.');
    }
}

}
