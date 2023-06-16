<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JeuResource;
use Exception;
use Illuminate\Http\Request;
use App\Models\Achat;
use App\Models\Jeu;
use Illuminate\Support\Facades\Auth;

class AchatController extends Controller
{
    //

    public function create(Request $request, $id)
    {
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {

            try {
                $jeu = Jeu::findOrFail($id);
                $achat = new Achat();
                $achat->date_achat = $request->date;//date('Y-m-d');
                $achat->lieu_achat = $request->lieu_achat;
                $achat->prix = $request->prix;
                $achat->user_id = Auth::user()->id;
                $achat->jeu_id = $id;
                $achat->save();
                return response()->json([
                        'status' => 'success',
                        'message' => 'Purchase created successfully',
                        'achat' => $achat,
                        'adherant' => Auth::user(),
                        'jeu' => new JeuResource($jeu)]
                    , 200);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'L\'achat n\'a pas pu être réalisé',
                    'errors' => $e,
                ], 422);
            }
        }
        return $this->throwUnauthorized();
    }

    public function update(Request $request,string $id)
    {
        $user_id = auth()->user()->id;
        $like = Achat::where('jeu_id', $id)
            ->where('user_id', $user_id)
            ->first();

        if ($like) {
            return $this->destroy($id);
        } else {
            return $this->create($request, $id);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function destroy(Request $request, $id)
    {
        $achat = Achat::where('jeu_id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$achat){
            return response()->json([
                'status' => 'false',
                'message' => 'Achat not found'
            ], 200);
        }
        if (Auth::user()->roles()->pluck('nom')->contains('adherent-premium')) {
            Achat::where('jeu_id', $id)
                ->where('user_id', Auth::user()->id)
                ->delete();
            //Ca ne marche pas, table pivot jsp comment fix
            return response()->json([
                'status' => 'success',
                'message' => 'Achat successfully deleted'
            ], 200);
        }

        return $this->throwUnauthorized();
    }


}
