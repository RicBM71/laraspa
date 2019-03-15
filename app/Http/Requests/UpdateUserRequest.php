<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        //ojo a esto: $this->user()->id (es lo mismo que auth()->user()->id
        // devolvería el id del usuario autenticado y
        // en este caso queremos comprobar con el usario a actualizar así que sería:
        // route('user') devuelve el objeto completo, en este caso para el id: $this->route('user')->id

        //unique:user,email,$id
        // $rules = [
        //     'name' => 'required|min:10'];

        $rules = [
            'name' => 'required',
            'blocked' => 'boolean',
            'username'=> ['required', Rule::unique('users')->ignore($this->route('user')->id)],
            'email'=> ['required','email', Rule::unique('users')->ignore($this->route('user')->id)]
        ];

        if ($this->filled('password')){ //va a actualizar la password
            $rules['password'] = ['confirmed','min:6'];
        }

        if ($this->filled('blocked_at')){
            $rules['blocked_at'] = ['date'];
        }

        // if ($this->filled('lastname')){
        //     $rules['lastname'] = ['alpha_num'];
        // }



       return $rules;
    }

    public function messages()
    {
        return [
             'unique'               => 'El :attribute ya ha sido registrado.',
             'lastname.alpha_num'   => 'En apellidos solo se permiten caracteres alfanuméricos'
        ];
    }
}
